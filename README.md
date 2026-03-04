# Laravel Multi-Vendor E-Commerce Platform

A full-stack, multi-vendor marketplace built with Laravel 12, implementing enterprise-grade architectural patterns including Repository Pattern, Service Layer, Observer Pattern, stored procedures, and role-based access control with multi-level admin scoping.

---

## Table of Contents

- [Business Perspective](#business-perspective)
- [Key Features](#key-features)
- [Technical Architecture](#technical-architecture)
- [Database Design Highlights](#database-design-highlights)
- [Security and Authorization](#security-and-authorization)
- [Performance and Scalability](#performance-and-scalability)
- [Testing Strategy](#testing-strategy)
- [Folder Structure Overview](#folder-structure-overview)
- [Installation Guide](#installation-guide)
- [Engineering Decisions and Architectural Highlights](#engineering-decisions-and-architectural-highlights)
- [Future Improvements](#future-improvements)
- [ATS Keywords](#ats-keywords)

---

## Business Perspective

### What Problem Does This Solve?

This platform addresses the core challenge of building a scalable online marketplace where multiple independent vendors can list, manage, and sell products through a single unified storefront, while a tiered administration layer (Admin and Super Admin) maintains oversight, quality control, and operational analytics.

### Who Would Use This?

Companies operating multi-vendor retail marketplaces, B2C platforms, or regional e-commerce ecosystems where vendor onboarding, commission tracking, order lifecycle management, and geographic admin scoping are critical business requirements.

### Business Model

The system simulates a commission-based marketplace model where vendors pay a configurable commission rate on each sale. The platform handles order routing, coupon validation, subscription plans for vendors, inventory tracking, and revenue reporting, providing the foundation for a real, revenue-generating marketplace.

### Why This Is More Than a Basic CRUD Project

- Implements a **state machine** for order lifecycle transitions with full audit history
- Features **ledger-style inventory tracking** with movement logs (in/out/adjustment)
- Enforces **geographic admin scoping** using city-based data isolation across all models
- Contains **7 MySQL stored procedures** for performance-critical aggregations
- Uses **polymorphic relationships** for flexible image and activity log management
- Applies the **Repository Pattern** with interface-driven dependency injection across 7 domain repositories
- Includes a dedicated **Service Layer** with 5 specialized services
- Has **55 test files** covering feature, unit, browser, and policy testing

---

## Key Features

### Marketplace Features

- Multi-vendor product catalog with variants (size, color, stock per variant)
- Category hierarchy with parent-child relationships
- Product search with full-text indexing on name and description
- Wishlist management with unique constraint enforcement
- Product and blog review/rating system with moderation workflow
- Coupon system with percentage/fixed discounts, usage limits, and date-based validity

### Admin Features

- Tiered admin dashboard with cached statistics (5-minute and 1-hour TTL tiers)
- Full CRUD management for users, products, orders, vendors, categories, coupons, plans, subscriptions, and blogs
- Activity log viewer for full audit trail across all models
- Global inventory movement tracking
- Geographic data scoping (admins see only data matching their city)
- Super Admin bypass for unrestricted access
- Vendor commission reporting via stored procedure

### Vendor Features

- Dedicated vendor dashboard with order and product statistics
- Product and variant management (create, edit, delete with ownership verification)
- Order management for vendor-specific order items
- Inventory management with stock adjustment logging
- Store settings and profile configuration

### Customer Features

- Shopping cart with variant selection
- Checkout flow with shipping/billing address management (JSON columns)
- Order placement with automatic order number generation
- Order tracking with full status history timeline
- Wishlist management
- Product review submission
- Subscription plan enrollment and cancellation
- Profile management with address, personal info, and vendor info sections

---

## Technical Architecture

### Architectural Patterns

| Pattern | Implementation |
|---|---|
| **Repository Pattern** | 8 repository interfaces with concrete implementations, bound via `AppServiceProvider` |
| **Service Layer** | 5 dedicated services: `DashboardService`, `OrderService`, `ImageUploadService`, `ReviewManagementService`, `SearchFilterService` |
| **Observer Pattern** | `OrderObserver` and `ProductObserver` for cache invalidation and status history logging |
| **MVC** | Standard Laravel MVC with dedicated controller namespaces per role (Admin, Vendor, User, Auth) |
| **Dependency Injection** | Interface-to-concrete bindings in service provider; singleton registration for shared services |
| **Enum Pattern** | `OrderStatus` PHP 8.1 backed enum with label method for status display |

### Role-Based Authorization

The system implements a four-tier role hierarchy:

1. **Super Admin** - Full platform access; bypasses all scoping and role restrictions
2. **Admin** - Geographic-scoped access; can manage resources within their city
3. **Vendor** - Manages own products, orders, and inventory; admin can also access vendor routes
4. **User (Customer)** - Places orders, manages cart/wishlist, writes reviews, manages subscriptions

Authorization is enforced through:
- `RoleMiddleware` for route-level access control
- `BlogPolicy`, `OrderPolicy`, and `SubscriptionPolicy` for resource-level authorization
- `CheckProductOwner` and `CheckBlogOwner` middleware for ownership verification

### Multi-Tenant Isolation Strategy

The `AdminScopeable` trait provides a unified, reusable admin scoping mechanism:

- Each model defines a `getAdminCityRelationPath()` method specifying the relationship chain to `user_addresses.city`
- The trait dynamically builds nested `whereHas` queries from dot-notation paths (e.g., `vendor.user.addresses`)
- Super Admins bypass all filters; regular Admins see only records matching their city
- Unauthorized roles receive a deny-all clause (`WHERE 1 = 0`)
- Applied to: `Product`, `Order`, `Blog`, `Vendor`, and `User` models

### Order Lifecycle / State Machine

Order status transitions follow a defined state machine:

```
pending -> processing -> shipped -> delivered -> completed
   |            |
   v            v
cancelled    cancelled
```

Enforcement exists at two levels:
- **Application Layer**: `OrderService::isValidTransition()` validates transitions in PHP
- **Database Layer**: `sp_order_status_transition` stored procedure enforces the same rules atomically with automatic history logging

Every status change is recorded in the `order_status_history` table via the `OrderObserver`.

### Caching Strategy

- **Dashboard statistics**: 5-minute TTL cache (`dashboard:stats`)
- **Chart data**: 1-hour TTL cache (`dashboard:charts`)
- **Recent items**: No cache (real-time data)
- **Cache invalidation**: Automatic via `OrderObserver` and `ProductObserver` calling `DashboardService::clearCache()`
- **Repository-level caching**: `CacheableRepository` trait provides `cacheFor()` and `invalidateCache()` with model-based key prefixing

---

## Database Design Highlights

### Schema Overview

- **24 migration files** defining the complete database schema
- **20 Eloquent models** covering all domain entities
- **17 model factories** for test data generation
- **19 seeder classes** for development data population

### Key Design Decisions

| Feature | Implementation |
|---|---|
| **Polymorphic Relationships** | `Image` model uses `imageable` morph for Products, Blogs, Users, and Vendors |
| **Polymorphic Activity Logging** | `ActivityLog` uses `trackable` and `causer` morph relationships |
| **JSON Columns** | `shipping_address` and `billing_address` stored as JSON in orders; `dimensions` stored as JSON in products |
| **Composite Indexes** | `idx_orders_status_created`, `idx_products_vendor_active`, `uq_wishlists_user_product`, `uq_product_reviews_user_product` |
| **Full-Text Search** | Applied to products (name + description), blogs (title + content), users (name + email), and coupons (code) |
| **Unique Constraints** | Prevent duplicate wishlist entries and duplicate product reviews per user |
| **Foreign Key Constraints** | Cascading deletes on user, product, order, and vendor relationships |
| **Enum Columns** | Order status, payment method, payment status, and inventory movement type |

### Stored Procedures (7 Total)

| Procedure | Purpose |
|---|---|
| `sp_admin_dashboard_stats` | Aggregated admin dashboard statistics across products, orders, users, and vendors |
| `sp_monthly_report` | Monthly revenue and order count report with configurable time range |
| `sp_vendor_dashboard` | Vendor-specific dashboard statistics |
| `sp_product_statistics` | Product catalog analytics (active/inactive/featured counts, price distribution) |
| `sp_apply_coupon` | Atomic coupon validation with usage limit, date range, minimum order, and type-based discount calculation |
| `sp_order_status_transition` | State machine enforcement with automatic history logging |
| `sp_vendor_commission_report` | Vendor commission and payout calculation by date range |

### Audit and History Tables

- **`activity_logs`**: Polymorphic audit trail capturing all CRUD operations with old/new value snapshots, IP address, and user agent
- **`order_status_history`**: Dedicated order transition log with from/to status, changed-by user, and notes
- **`inventory_movements`**: Ledger-style stock tracking with movement type, quantity, previous/new stock, and reference entity

---

## Security and Authorization

### RBAC Strategy

- Four distinct roles: `superadmin`, `admin`, `vendor`, `user`
- Role stored as a column on the `users` table
- `RoleMiddleware` enforces route-level access with Super Admin bypass and admin-to-vendor escalation

### Policy-Based Authorization

| Policy | Coverage |
|---|---|
| `BlogPolicy` | viewAny, view, create, update, delete with admin scoping |
| `OrderPolicy` | Role-based view/update (admin full access, vendor product-scoped, user own-only), admin-only delete |
| `SubscriptionPolicy` | Admin-only management, user can view own subscription |

### Middleware Stack

| Middleware | Purpose |
|---|---|
| `RoleMiddleware` | Validates user role against route requirements |
| `CheckProductOwner` | Verifies product ownership for vendor edit/delete operations |
| `CheckBlogOwner` | Verifies blog authorship before modification |

### Data Protection

- Password hashing via Laravel's `hashed` cast
- Sensitive attributes (`password`, `remember_token`) excluded from activity logs
- CSRF protection via Laravel's built-in middleware
- IDOR prevention through ownership checks in middleware and policies
- Input sanitization via model mutators (phone number cleaning, HTML stripping, name normalization)

---

## Performance and Scalability

### Indexing Strategy

**Performance Indexes (B-tree):**
- Orders: status, payment_status, payment_method, created_at, composite (status + created_at)
- Products: is_active, is_featured, price, composite (vendor_id + is_active)
- Categories: parent_id, is_active
- Users: role, status
- User Addresses: city (critical for admin scoping)
- Coupons: is_active, composite (valid_from + valid_to)
- Subscriptions: status, user_id

**Full-Text Indexes:**
- Products: name + description
- Blogs: title + content
- Users: name + email
- Coupons: code

**Integrity Indexes:**
- Unique: wishlists (user_id + product_id), product_reviews (user_id + product_id)
- Polymorphic composite: images (imageable_type + imageable_id)

### Query Optimization

- Stored procedures replace multi-query patterns for dashboard analytics
- `DashboardService` uses raw SQL with `DB::selectOne()` for single-row aggregations
- Selective column loading: `select('id', 'name', ...)` on recent items queries
- Eager loading with specific columns: `with('user:id,name')`, `with('vendor:id,store_name')`
- Tiered cache invalidation to minimize stale data while reducing query load

### Scalability Considerations

- Migrations document design intent for 1M+ record performance
- Composite indexes target the most common filter combinations
- Full-text indexes replace `LIKE '%keyword%'` patterns (documented as ~100x faster at scale)
- Stored procedures reduce network round trips for complex aggregations

---

## Testing Strategy

### Test Coverage

| Category | Files | Focus Areas |
|---|---|---|
| **Feature Tests** | 39 files | Admin CRUD (11), Auth flows (6), Vendor operations (3), User features (1), Orders, Products, Blogs, Profiles, Authorization, Wishlists, Seeders |
| **Unit Tests** | 7 files | Policy tests (3), Model structure (3), Example test (1) |
| **Browser Tests** | 6 files | Dusk-based end-to-end tests with page objects |
| **Total** | **55 test files** | |

### Testing Approach

- **Pest PHP** as test runner with Laravel plugin
- **17 model factories** generating realistic test data using Faker
- **19 seeders** for development database population
- **Policy tests** covering `BlogPolicy`, `OrderPolicy`, and `SubscriptionPolicy` with role-based assertions
- **Admin feature tests** covering dashboard, CRUD operations for all managed resources (users, products, orders, vendors, categories, coupons, plans, subscriptions, blogs, activity logs)
- **Authorization tests** verifying role-based access control across routes
- **Vendor tests** covering inventory management, order handling, and profile updates
- **Browser tests** using Laravel Dusk for end-to-end UI verification

---

## Folder Structure Overview

```
app/
├── Enums/                  # PHP 8.1 backed enums (OrderStatus)
├── Helpers/                # Helper utilities
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # 12 admin controllers (CRUD for all resources)
│   │   ├── Auth/           # 9 authentication controllers (Breeze)
│   │   ├── User/           # User dashboard, subscriptions, order tracking
│   │   ├── Vendor/         # Vendor inventory and profile management
│   │   └── all_pages/      # Public-facing controllers (products, blogs, index)
│   ├── Middleware/          # RoleMiddleware, CheckProductOwner, CheckBlogOwner
│   └── Requests/           # 19 form request classes with validation rules
├── Models/                 # 20 Eloquent models with relationships and mutators
├── Observers/              # OrderObserver, ProductObserver
├── Policies/               # BlogPolicy, OrderPolicy, SubscriptionPolicy
├── Providers/              # AppServiceProvider (DI bindings, observer registration)
├── Repositories/
│   ├── Contracts/          # 8 repository interfaces
│   ├── BaseRepository.php  # Abstract base with 25+ chainable query methods
│   └── [7 concrete repos]  # Product, Order, User, Vendor, Blog, Category, Coupon
├── Services/               # 5 dedicated services
│   ├── DashboardService    # Cached dashboard analytics with tiered TTL
│   ├── OrderService        # Order business logic and state validation
│   ├── ImageUploadService  # Polymorphic image management
│   ├── ReviewManagementService  # Product and blog review operations
│   └── SearchFilterService # Reusable query filter builders
├── Traits/                 # 3 production traits
│   ├── AdminScopeable      # Unified geographic admin scoping
│   ├── CacheableRepository # Repository-level caching with key-prefix system
│   └── HasActivityLog      # Automatic audit logging with old/new snapshots
└── View/                   # View composers and components

database/
├── factories/              # 17 model factories
├── migrations/             # 24 migrations (schema, indexes, stored procedures)
└── seeders/                # 19 seeders including comprehensive demo data

routes/
├── web.php                 # Public routes (products, blogs, profile)
├── admin.php               # Admin routes with role middleware
├── vendor.php              # Vendor routes with role middleware
├── user.php                # Customer routes with role middleware
├── superadmin.php          # Super admin routes (scaffolded)
└── auth.php                # Authentication routes (Laravel Breeze)

tests/
├── Feature/                # 39 feature test files
│   ├── Admin/              # 11 admin CRUD tests
│   ├── Auth/               # 6 authentication tests
│   ├── Vendor/             # 3 vendor operation tests
│   └── User/               # 1 user feature test
├── Unit/                   # 7 unit test files
│   ├── Models/             # Model structure tests
│   └── Policies/           # Policy authorization tests
└── Browser/                # 6 Dusk browser tests
```

---

## Installation Guide

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL 5.7+ (required for stored procedures and full-text indexes)

### Step-by-Step Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd ecommerce
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database connection**  
   Update `.env` with your MySQL credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ecommerce
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database**
   ```bash
   php artisan db:seed
   ```

8. **Link storage**
   ```bash
   php artisan storage:link
   ```

9. **Build frontend assets**
   ```bash
   npm run dev
   ```

10. **Start the development server**
    ```bash
    php artisan serve
    ```

    Or use the combined dev command:
    ```bash
    composer dev
    ```
    This starts the server, queue listener, and Vite concurrently.

### Running Tests

```bash
php artisan test
```

Or using Pest directly:
```bash
./vendor/bin/pest
```

---

## Engineering Decisions and Architectural Highlights

### Dependency Inversion via Repository Contracts

All 7 domain repositories are bound through interfaces in `AppServiceProvider`. Controllers depend on abstractions (`ProductRepositoryInterface`), not concrete classes. This enables swapping implementations (e.g., caching decorator, Elasticsearch-backed repository) without modifying consumer code.

### Unified Admin Scoping via Trait Abstraction

The `AdminScopeable` trait replaces duplicated `ForAdmin` scopes across 5 models. Each model defines only a relationship path string (e.g., `vendor.user.addresses`), and the trait dynamically constructs nested `whereHas` queries. This is a textbook application of the Template Method pattern.

### Observer-Driven Cache Invalidation

`OrderObserver` and `ProductObserver` automatically invalidate dashboard caches on create, update, and delete events. The `OrderObserver` also maintains the `order_status_history` audit trail, ensuring data consistency without polluting controller logic.

### Ledger-Style Inventory Tracking

The `inventory_movements` table records every stock change with:
- Movement type (in, out, adjustment)
- Previous and new stock values
- Reference to the triggering entity (polymorphic)
- User who performed the action

This provides a complete, auditable inventory history rather than just a current stock count.

### State Machine Enforcement at Two Layers

Order status transitions are validated both in PHP (`OrderService::isValidTransition()`) and in MySQL (`sp_order_status_transition`). The stored procedure provides atomic enforcement with automatic history logging, preventing race conditions in concurrent environments.

### Activity Logging with Sensitive Data Filtering

The `HasActivityLog` trait automatically captures old/new value snapshots for every model event but explicitly excludes sensitive fields (`password`, `remember_token`). It tracks IP address and user agent for security auditing.

### Service Layer Separation

Business logic is extracted from controllers into dedicated services:
- `DashboardService` encapsulates all dashboard analytics with tiered caching
- `OrderService` handles order calculations and state validation
- `ImageUploadService` manages polymorphic image upload, update, and deletion
- `ReviewManagementService` handles review creation, moderation, statistics, and bulk operations
- `SearchFilterService` provides reusable filter builders for orders, products, blogs, users, and vendors

### Defensive Programming in Model Mutators

Models implement input sanitization at the attribute level:
- Phone numbers stripped of non-numeric characters
- Names normalized with `trim(ucwords(strtolower()))`
- Prices rounded to 2 decimal places
- Vendor descriptions stripped of HTML tags
- Default values assigned for missing avatars
- Automatic slug generation from store names and blog titles

---

## Future Improvements

- **RESTful API Layer** - Add API resource controllers with token-based authentication (Laravel Sanctum) for mobile app integration
- **Payment Gateway Integration** - Integrate Stripe or PayPal for real payment processing during checkout
- **Queue-Based Processing** - Move email notifications, inventory recalculations, and report generation to background queues
- **Docker Containerization** - Add Docker Compose configuration for consistent development and deployment environments
- **CI/CD Pipeline** - Configure GitHub Actions or GitLab CI for automated testing, linting, and deployment
- **Rate Limiting** - Apply throttle middleware to authentication, API, and review submission endpoints
- **Elasticsearch Integration** - Replace MySQL full-text search with Elasticsearch for advanced product search with faceted filtering
- **Real-Time Notifications** - Implement WebSocket-based notifications for order status updates using Laravel Broadcasting
- **Multi-Currency Support** - Add currency conversion and locale-aware pricing
- **Reporting Dashboard** - Expand vendor commission reporting with exportable PDF/CSV reports

---

## ATS Keywords

Laravel, PHP 8.2, MySQL, Eloquent ORM, Repository Pattern, Service Layer, MVC Architecture, SOLID Principles, Dependency Injection, Observer Pattern, State Machine, Role-Based Access Control (RBAC), Policy-Based Authorization, Middleware, REST API, Multi-Vendor Marketplace, E-Commerce Platform, Stored Procedures, Database Indexing, Full-Text Search, Composite Indexes, Polymorphic Relationships, JSON Columns, Query Optimization, Caching Strategy, Cache Invalidation, Activity Logging, Audit Trail, Inventory Management, Order Lifecycle, Unit Testing, Feature Testing, Browser Testing, Pest PHP, Laravel Dusk, Factory Pattern, Database Seeding, Form Request Validation, Blade Templating, Vite, Tailwind CSS, Git, Composer, npm, Laravel Breeze, Authentication, Email Verification, Password Reset, Input Sanitization, CSRF Protection, Multi-Tenant Architecture, Geographic Scoping, Commission Tracking, Coupon System, Subscription Management, Image Upload, Code Architecture, Clean Code, Design Patterns, Template Method Pattern, Defensive Programming.

---

## Tech Stack

| Layer | Technology |
|---|---|
| **Backend Framework** | Laravel 12 |
| **Language** | PHP 8.2 |
| **Database** | MySQL |
| **Frontend** | Blade Templates, Tailwind CSS, Vite |
| **Authentication** | Laravel Breeze |
| **Testing** | Pest PHP, Laravel Dusk |
| **Package Manager** | Composer, npm |

---

## License

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).
