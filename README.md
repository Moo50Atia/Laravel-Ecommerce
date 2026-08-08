# Multi-Vendor E-Commerce Backend Engine

[![Laravel](https://img.shields.io/badge/Laravel-11.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Testing](https://img.shields.io/badge/Testing-Pest-purple?style=for-the-badge)](https://pestphp.com)

---

## 🎯 Product Overview & Business Value

This repository contains a high-performance **Multi-Vendor E-Commerce Backend Engine** designed to handle complex marketplace operations, including product variant management, dynamic coupon discounting, vendor commission tracking, subscription billing, and transactional inventory processing.

### Core Business Capabilities
* **Multi-Vendor Marketplace**: Vendor store management, catalog administration, commission calculations, and sales analytics.
* **Transactional Order Processing**: Multi-item cart checkout, coupon verification, order lifecycle management, and address handling.
* **Audit Logging & Inventory Control**: Real-time stock movement tracking and historical order status auditing.

---

## ⚙️ Database Engineering & Backend Architecture

This project prioritizes **database performance, data integrity, and architectural maintainability** to survive high-volume transactional workloads.

```
┌────────────────────────────────────────────────────────────────────────────────────────┐
│                              DATABASE & BACKEND ARCHITECTURE                           │
│                                                                                        │
│  [ Order / Product Requests ]                                                          │
│              │                                                                         │
│              ▼                                                                         │
│  [ Decoupled Service Layer ] ──► (OrderService, SearchFilterService, ReviewService)     │
│              │                                                                         │
│              ▼                                                                         │
│  [ Repository Layer ]       ──► (ProductRepository, OrderRepository, VendorRepository) │
│              │                                                                         │
│              ▼                                                                         │
│  [ Model Observers ]        ──► Triggers Audit Logs (OrderStatusHistory, Inventory)  │
│              │                                                                         │
│              ▼                                                                         │
│  [ MySQL Engine ]           ──► Performance Composite Indexes + Stored Procedures     │
└────────────────────────────────────────────────────────────────────────────────────────┘
```

### Key Engineering Contributions Implemented:

1. **Custom Database Performance Indexing (`database/migrations`)**:
   * Engineered composite indexes on high-frequency query columns (`2026_02_11_160001_add_performance_indexes.php`) to optimize multi-field search and filtering.
   * Configured `FULLTEXT` search indexes (`2026_02_11_160003_add_fulltext_indexes.php`) on product catalog names and descriptions for fast text matching.
   * Applied unique database constraints (`2026_02_11_160002_add_integrity_indexes.php`) to guarantee data integrity under concurrent write operations.

2. **MySQL Stored Procedures (`2026_02_11_160004_create_stored_procedures.php`)**:
   * Encapsulated complex financial calculations and inventory stock recalculations directly inside database stored procedures for optimal execution speed.

3. **Immutable Audit Logging & Inventory History**:
   * Implemented dedicated history and audit tables (`inventory_movements`, `order_status_history`, `activity_logs`).
   * Utilized **Laravel Model Observers** (`app/Observers`) to automatically capture and record stock level changes and status state transitions whenever orders are updated.

4. **Service-Repository Architecture**:
   * Decoupled controller logic into dedicated Service classes (`OrderService`, `ReviewManagementService`, `SearchFilterService`, `DashboardService`).
   * Implemented Repository interfaces (`app/Repositories/Contracts`) for products, orders, vendors, and users to isolate database queries from HTTP controllers.

---

## 💻 Technology Stack

* **Framework & Language**: Laravel (PHP 8.2+)
* **Database Engine**: MySQL 8.0 (Custom Performance Migrations, Indexes & Stored Procedures)
* **Design Patterns**: Repository Pattern with Contracts, Service Layer, Model Observers, Typed Enums
* **Testing Suite**: Pest PHP, PHPUnit

---

## ⚡ Quick Start & Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Moo50Atia/Laravel-Ecommerce.git
   cd Laravel-Ecommerce
   ```

2. **Install Composer dependencies**:
   ```bash
   composer install
   ```

3. **Environment Setup & Run Migrations**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --seed
   ```

4. **Execute Test Suite**:
   ```bash
   php artisan test
   ```
