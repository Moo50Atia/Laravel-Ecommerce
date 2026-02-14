<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Creates stored procedures for performance-critical operations.
 * These replace multi-query patterns that scale poorly at 1M+ records.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. Admin Dashboard Statistics ─────────────────────
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_admin_dashboard_stats;
            CREATE PROCEDURE sp_admin_dashboard_stats(IN p_admin_city VARCHAR(100))
            BEGIN
                -- Product statistics
                SELECT
                    COUNT(*) AS total_products,
                    COALESCE(SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END), 0) AS active_products,
                    COALESCE(SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END), 0) AS inactive_products,
                    COALESCE(SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END), 0) AS featured_products,
                    COALESCE(AVG(price), 0) AS avg_price
                FROM products;

                -- Order statistics
                SELECT
                    COUNT(*) AS total_orders,
                    COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) AS pending_orders,
                    COALESCE(SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END), 0) AS processing_orders,
                    COALESCE(SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END), 0) AS completed_orders,
                    COALESCE(SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END), 0) AS cancelled_orders,
                    COALESCE(SUM(CASE WHEN status != 'cancelled' THEN grand_total ELSE 0 END), 0) AS total_revenue,
                    COALESCE(AVG(CASE WHEN status != 'cancelled' THEN grand_total END), 0) AS avg_order_value
                FROM orders;

                -- User statistics
                SELECT
                    COUNT(*) AS total_users,
                    COALESCE(SUM(CASE WHEN role = 'user' THEN 1 ELSE 0 END), 0) AS customers,
                    COALESCE(SUM(CASE WHEN role = 'vendor' THEN 1 ELSE 0 END), 0) AS vendors_count,
                    COALESCE(SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END), 0) AS admins,
                    COALESCE(SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END), 0) AS active_users
                FROM users;

                -- Vendor statistics
                SELECT
                    COUNT(*) AS total_vendors,
                    COALESCE(AVG(rating), 0) AS avg_rating,
                    COALESCE(AVG(commission_rate), 0) AS avg_commission
                FROM vendors;
            END
        ");

        // ─── 2. Monthly Revenue & Order Report ─────────────────
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_monthly_report;
            CREATE PROCEDURE sp_monthly_report(IN p_months INT)
            BEGIN
                SELECT
                    DATE_FORMAT(created_at, '%Y-%m') AS month_key,
                    DATE_FORMAT(created_at, '%b %Y') AS month_label,
                    COUNT(*) AS order_count,
                    COALESCE(SUM(CASE WHEN status != 'cancelled' THEN grand_total ELSE 0 END), 0) AS revenue,
                    COALESCE(AVG(CASE WHEN status != 'cancelled' THEN grand_total END), 0) AS avg_order_value
                FROM orders
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL p_months MONTH)
                GROUP BY month_key, month_label
                ORDER BY month_key ASC;
            END
        ");

        // ─── 3. Vendor Dashboard Stats ─────────────────────────
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_vendor_dashboard;
            CREATE PROCEDURE sp_vendor_dashboard(IN p_vendor_id BIGINT UNSIGNED)
            BEGIN
                SELECT
                    (SELECT COUNT(*) FROM products WHERE vendor_id = p_vendor_id) AS total_products,
                    (SELECT COUNT(*) FROM products WHERE vendor_id = p_vendor_id AND is_active = 1) AS active_products,
                    COUNT(*) AS total_orders,
                    COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) AS pending_orders,
                    COALESCE(SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END), 0) AS processing_orders,
                    COALESCE(SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END), 0) AS completed_orders,
                    COALESCE(SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END), 0) AS delivered_orders,
                    COALESCE(SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END), 0) AS cancelled_orders,
                    COALESCE(SUM(CASE WHEN status != 'cancelled' THEN grand_total ELSE 0 END), 0) AS total_revenue
                FROM orders
                WHERE vendor_id = p_vendor_id;
            END
        ");

        // ─── 4. Product Statistics ─────────────────────────────
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_product_statistics;
            CREATE PROCEDURE sp_product_statistics()
            BEGIN
                SELECT
                    COUNT(*) AS total,
                    COALESCE(SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END), 0) AS active,
                    COALESCE(SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END), 0) AS inactive,
                    COALESCE(SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END), 0) AS featured,
                    COALESCE(AVG(price), 0) AS avg_price,
                    COALESCE(MIN(price), 0) AS min_price,
                    COALESCE(MAX(price), 0) AS max_price
                FROM products;
            END
        ");

        // ─── 5. Apply Coupon (Atomic validation) ───────────────
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_apply_coupon;
            CREATE PROCEDURE sp_apply_coupon(
                IN p_coupon_code VARCHAR(50),
                IN p_order_total DECIMAL(10,2),
                OUT p_discount DECIMAL(10,2),
                OUT p_valid TINYINT,
                OUT p_message VARCHAR(255)
            )
            BEGIN
                DECLARE v_coupon_id BIGINT;
                DECLARE v_type VARCHAR(20);
                DECLARE v_value DECIMAL(10,2);
                DECLARE v_min_order DECIMAL(10,2);
                DECLARE v_max_uses INT;
                DECLARE v_current_uses INT;
                DECLARE v_valid_from DATE;
                DECLARE v_valid_to DATE;
                DECLARE v_is_active TINYINT;

                SET p_discount = 0;
                SET p_valid = 0;

                SELECT id, type, value, min_order_amount, max_uses, used_count,
                       valid_from, valid_to, is_active
                INTO v_coupon_id, v_type, v_value, v_min_order, v_max_uses, v_current_uses,
                     v_valid_from, v_valid_to, v_is_active
                FROM coupons WHERE code = p_coupon_code LIMIT 1;

                IF v_coupon_id IS NULL THEN
                    SET p_message = 'Coupon not found';
                ELSEIF v_is_active = 0 THEN
                    SET p_message = 'Coupon is inactive';
                ELSEIF CURDATE() < v_valid_from THEN
                    SET p_message = 'Coupon is not yet valid';
                ELSEIF CURDATE() > v_valid_to THEN
                    SET p_message = 'Coupon has expired';
                ELSEIF v_max_uses IS NOT NULL AND v_current_uses >= v_max_uses THEN
                    SET p_message = 'Coupon usage limit reached';
                ELSEIF v_min_order IS NOT NULL AND p_order_total < v_min_order THEN
                    SET p_message = CONCAT('Minimum order amount is ', v_min_order);
                ELSE
                    IF v_type = 'percentage' THEN
                        SET p_discount = ROUND(p_order_total * v_value / 100, 2);
                    ELSE
                        SET p_discount = LEAST(v_value, p_order_total);
                    END IF;
                    SET p_valid = 1;
                    SET p_message = 'Coupon applied successfully';
                END IF;
            END
        ");

        // ─── 6. Order Status Transition (State Machine) ────────
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_order_status_transition;
            CREATE PROCEDURE sp_order_status_transition(
                IN p_order_id BIGINT UNSIGNED,
                IN p_new_status VARCHAR(20),
                IN p_admin_id BIGINT UNSIGNED,
                IN p_notes TEXT,
                OUT p_success TINYINT,
                OUT p_message VARCHAR(255)
            )
            BEGIN
                DECLARE v_current_status VARCHAR(20);
                DECLARE v_valid TINYINT DEFAULT 0;

                SET p_success = 0;

                SELECT status INTO v_current_status FROM orders WHERE id = p_order_id;

                IF v_current_status IS NULL THEN
                    SET p_message = 'Order not found';
                ELSE
                    -- Validate state transitions
                    IF v_current_status = 'pending' AND p_new_status IN ('processing', 'cancelled') THEN
                        SET v_valid = 1;
                    ELSEIF v_current_status = 'processing' AND p_new_status IN ('shipped', 'cancelled') THEN
                        SET v_valid = 1;
                    ELSEIF v_current_status = 'shipped' AND p_new_status IN ('delivered') THEN
                        SET v_valid = 1;
                    ELSEIF v_current_status = 'delivered' AND p_new_status IN ('completed') THEN
                        SET v_valid = 1;
                    END IF;

                    IF v_valid = 1 THEN
                        UPDATE orders SET status = p_new_status, updated_at = NOW() WHERE id = p_order_id;

                        -- Log the transition
                        INSERT INTO order_status_history (order_id, from_status, to_status, changed_by, notes, created_at)
                        VALUES (p_order_id, v_current_status, p_new_status, p_admin_id, p_notes, NOW());

                        SET p_success = 1;
                        SET p_message = CONCAT('Status changed from ', v_current_status, ' to ', p_new_status);
                    ELSE
                        SET p_message = CONCAT('Invalid transition: ', v_current_status, ' -> ', p_new_status);
                    END IF;
                END IF;
            END
        ");

        // ─── 7. Vendor Commission Report ───────────────────────
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_vendor_commission_report;
            CREATE PROCEDURE sp_vendor_commission_report(IN p_date_from DATE, IN p_date_to DATE)
            BEGIN
                SELECT
                    v.id AS vendor_id,
                    v.store_name,
                    v.commission_rate,
                    COUNT(DISTINCT o.id) AS total_orders,
                    COALESCE(SUM(oi.price * oi.quantity), 0) AS total_sales,
                    COALESCE(SUM(oi.price * oi.quantity * v.commission_rate / 100), 0) AS commission_amount,
                    COALESCE(SUM(oi.price * oi.quantity) - SUM(oi.price * oi.quantity * v.commission_rate / 100), 0) AS vendor_payout
                FROM vendors v
                LEFT JOIN order_items oi ON oi.vendor_id = v.id
                LEFT JOIN orders o ON o.id = oi.order_id
                    AND o.status != 'cancelled'
                    AND o.created_at BETWEEN p_date_from AND p_date_to
                GROUP BY v.id, v.store_name, v.commission_rate
                ORDER BY total_sales DESC;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_dashboard_stats');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_monthly_report');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_vendor_dashboard');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_product_statistics');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_apply_coupon');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_order_status_transition');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_vendor_commission_report');
    }
};
