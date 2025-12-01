<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ✅ 1️⃣ Kiểm tra tồn kho trước khi thêm order_item
        DB::unprepared('
            CREATE TRIGGER trg_check_stock_before_insert
            BEFORE INSERT ON order_items
            FOR EACH ROW
            BEGIN
                DECLARE available_stock INT;
                SELECT StockQuantity INTO available_stock FROM products WHERE ProductID = NEW.ProductID;

                IF available_stock < NEW.Quantity THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Không thể đặt hàng: sản phẩm đã hết hàng hoặc không đủ số lượng tồn kho.";
                END IF;
            END
        ');

        // ✅ 2️⃣ Tự động trừ số lượng sau khi thêm order_item
        DB::unprepared('
            CREATE TRIGGER trg_reduce_stock_after_insert
            AFTER INSERT ON order_items
            FOR EACH ROW
            BEGIN
                UPDATE products
                SET StockQuantity = StockQuantity - NEW.Quantity
                WHERE ProductID = NEW.ProductID;
            END
        ');

        // ✅ 3️⃣ Tự động cộng lại số lượng nếu order_item bị xóa
        DB::unprepared('
            CREATE TRIGGER trg_restore_stock_after_delete
            AFTER DELETE ON order_items
            FOR EACH ROW
            BEGIN
                UPDATE products
                SET StockQuantity = StockQuantity + OLD.Quantity
                WHERE ProductID = OLD.ProductID;
            END
        ');

        // ✅ Cart: kiểm tra tồn kho trước khi thêm vào giỏ (không cho quantity vượt quá StockQuantity)
        DB::unprepared('
            CREATE TRIGGER trg_check_stock_before_insert_cart
            BEFORE INSERT ON cart_items
            FOR EACH ROW
            BEGIN
                DECLARE available_stock INT;
                DECLARE total_in_cart INT;
                
                SELECT StockQuantity INTO available_stock FROM products WHERE ProductID = NEW.ProductID;
                
                -- Tính tổng số lượng của sản phẩm này đã có trong giỏ
                SELECT COALESCE(SUM(Quantity), 0) INTO total_in_cart 
                FROM cart_items 
                WHERE ProductID = NEW.ProductID;
                
                -- Kiểm tra: tổng trong giỏ + số lượng mới <= tồn kho
                IF (total_in_cart + NEW.Quantity) > available_stock THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Không thể thêm vào giỏ: không đủ tồn kho.";
                END IF;
            END
        ');

        // ✅ Cart: kiểm tra tồn kho trước khi cập nhật giỏ hàng (nếu tăng số lượng hoặc đổi sản phẩm)
        DB::unprepared('
            CREATE TRIGGER trg_check_stock_before_update_cart
            BEFORE UPDATE ON cart_items
            FOR EACH ROW
            BEGIN
                DECLARE available_stock INT;
                DECLARE total_in_cart INT;

                IF NEW.ProductID = OLD.ProductID AND NEW.Quantity > OLD.Quantity THEN
                    SELECT StockQuantity INTO available_stock FROM products WHERE ProductID = NEW.ProductID;
                    
                    -- Tính tổng số lượng của sản phẩm này trong giỏ (không tính item hiện tại)
                    SELECT COALESCE(SUM(Quantity), 0) INTO total_in_cart 
                    FROM cart_items 
                    WHERE ProductID = NEW.ProductID AND CartItemID != NEW.CartItemID;
                    
                    -- Kiểm tra: tổng trong giỏ + số lượng mới <= tồn kho
                    IF (total_in_cart + NEW.Quantity) > available_stock THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Không thể tăng số lượng trong giỏ: không đủ tồn kho.";
                    END IF;

                ELSEIF NEW.ProductID <> OLD.ProductID THEN
                    SELECT StockQuantity INTO available_stock FROM products WHERE ProductID = NEW.ProductID;
                    
                    -- Tính tổng số lượng của sản phẩm mới trong giỏ
                    SELECT COALESCE(SUM(Quantity), 0) INTO total_in_cart 
                    FROM cart_items 
                    WHERE ProductID = NEW.ProductID AND CartItemID != NEW.CartItemID;
                    
                    -- Kiểm tra: tổng trong giỏ + số lượng mới <= tồn kho
                    IF (total_in_cart + NEW.Quantity) > available_stock THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Không thể đổi sản phẩm trong giỏ: sản phẩm mới không đủ tồn kho.";
                    END IF;
                END IF;
            END
        ');

        // ✅ 4️⃣ Kiểm tra tồn kho trước khi cập nhật order_item (nếu tăng số lượng hoặc đổi sản phẩm)
        DB::unprepared('
            CREATE TRIGGER trg_check_stock_before_update
            BEFORE UPDATE ON order_items
            FOR EACH ROW
            BEGIN
                DECLARE available_stock INT;
                DECLARE need INT;

                -- Nếu cùng sản phẩm và số lượng tăng
                IF NEW.ProductID = OLD.ProductID AND NEW.Quantity > OLD.Quantity THEN
                    SET need = NEW.Quantity - OLD.Quantity;
                    SELECT StockQuantity INTO available_stock FROM products WHERE ProductID = NEW.ProductID;

                    IF available_stock < need THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Không thể tăng số lượng: không đủ tồn kho.";
                    END IF;

                -- Nếu đổi sang sản phẩm khác, cần kiểm tra sản phẩm mới có đủ cho số lượng mới
                ELSEIF NEW.ProductID <> OLD.ProductID THEN
                    SELECT StockQuantity INTO available_stock FROM products WHERE ProductID = NEW.ProductID;

                    IF available_stock < NEW.Quantity THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Không thể đổi sản phẩm: sản phẩm mới không đủ tồn kho.";
                    END IF;
                END IF;
            END
        ');

        // ✅ 5️⃣ Điều chỉnh tồn kho sau khi cập nhật order_item (ghép cả tăng/giảm/đổi sản phẩm)
        DB::unprepared('
            CREATE TRIGGER trg_adjust_stock_after_update
            AFTER UPDATE ON order_items
            FOR EACH ROW
            BEGIN
                -- Nếu cùng sản phẩm, điều chỉnh bằng hiệu (dương: trừ thêm, âm: cộng lại)
                IF NEW.ProductID = OLD.ProductID THEN
                    UPDATE products
                    SET StockQuantity = StockQuantity - (NEW.Quantity - OLD.Quantity)
                    WHERE ProductID = NEW.ProductID;

                -- Nếu đổi sản phẩm: hoàn trả số lượng cũ cho sản phẩm cũ, trừ số lượng mới cho sản phẩm mới
                ELSE
                    UPDATE products
                    SET StockQuantity = StockQuantity + OLD.Quantity
                    WHERE ProductID = OLD.ProductID;

                    UPDATE products
                    SET StockQuantity = StockQuantity - NEW.Quantity
                    WHERE ProductID = NEW.ProductID;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_check_stock_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_reduce_stock_after_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_restore_stock_after_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_check_stock_before_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_adjust_stock_after_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_check_stock_before_insert_cart');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_check_stock_before_update_cart');
    }
};
