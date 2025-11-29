<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check stock before inserting into cart_items
        DB::unprepared('
            CREATE TRIGGER trg_check_stock_before_insert_cart_items
            BEFORE INSERT ON cart_items
            FOR EACH ROW
            BEGIN
                DECLARE available_stock INT;
                SELECT StockQuantity INTO available_stock FROM products WHERE ProductID = NEW.ProductID;

                IF available_stock < NEW.Quantity THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Không thể thêm vào giỏ: không đủ tồn kho.";
                END IF;
            END
        ');

        // Check stock before updating cart_items
        DB::unprepared('
            CREATE TRIGGER trg_check_stock_before_update_cart_items
            BEFORE UPDATE ON cart_items
            FOR EACH ROW
            BEGIN
                DECLARE available_stock INT;

                IF NEW.ProductID = OLD.ProductID AND NEW.Quantity > OLD.Quantity THEN
                    SELECT StockQuantity INTO available_stock FROM products WHERE ProductID = NEW.ProductID;

                    IF available_stock < NEW.Quantity THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Không thể tăng số lượng trong giỏ: không đủ tồn kho.";
                    END IF;

                ELSEIF NEW.ProductID <> OLD.ProductID THEN
                    SELECT StockQuantity INTO available_stock FROM products WHERE ProductID = NEW.ProductID;
                    IF available_stock < NEW.Quantity THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Không thể đổi sản phẩm trong giỏ: sản phẩm mới không đủ tồn kho.";
                    END IF;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_check_stock_before_insert_cart_items');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_check_stock_before_update_cart_items');
    }
};
