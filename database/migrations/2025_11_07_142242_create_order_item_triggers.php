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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_check_stock_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_reduce_stock_after_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_restore_stock_after_delete');
    }
};
