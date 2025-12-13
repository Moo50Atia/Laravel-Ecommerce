# E-Commerce Platform

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

## About The Project

This is a comprehensive e-commerce platform built with Laravel, designed to provide a seamless shopping experience for customers and powerful management tools for vendors and administrators. The platform supports multiple user roles, product management, order processing, and more.

## Features

### Multi-Role User System
- **Customer**: Browse products, manage wishlist, place orders, track deliveries
- **Vendor**: Manage store, add products, process orders, track sales
- **Admin**: Oversee platform, manage users, approve vendors, monitor transactions
- **Superadmin**: Full system access and control

### Product Management
- Product listing with detailed descriptions
- Product variants (size, color, etc.)
- Product categories and filtering
- Product reviews and ratings
- Featured products showcase

### Shopping Experience
- User-friendly product browsing
- Advanced search functionality
- Wishlist management
- Shopping cart
- Secure checkout process
- Order tracking

### Vendor Management
- Vendor registration and profile management
- Product inventory management
- Order processing workflow
- Sales analytics and reporting
- Commission system

### Admin Dashboard
- Comprehensive analytics
- User management
- Vendor approval and monitoring
- Product oversight
- Order management
- Content management for blogs

### Additional Features
- Blog system with reviews
- Coupon and discount management
- Email notifications
- Mobile-responsive design

## User Journey

### Customer Journey

1. **Registration & Login**
   - Register as a customer or vendor
   - Login to access personalized features

2. **Browsing & Shopping**
   - Browse products by category
   - Search for specific items
   - View detailed product information
   - Read product reviews
   - Add products to wishlist
   - Add products to cart

3. **Checkout Process**
   - Review cart items
   - Apply coupons if available
   - Enter shipping information
   - Select payment method
   - Complete purchase

4. **Post-Purchase**
   - Track order status
   - View order history
   - Leave product reviews
   - Manage wishlist
   - Contact support if needed

### Vendor Journey

1. **Registration & Setup**
   - Register as a vendor
   - Complete store profile
   - Set up commission rates

2. **Product Management**
   - Add new products
   - Create product variants
   - Upload product images
   - Set pricing and inventory
   - Manage product categories

3. **Order Management**
   - Receive order notifications
   - Process new orders
   - Update order status
   - Handle shipping and delivery

4. **Store Management**
   - Monitor sales analytics
   - Track commission payments
   - Respond to customer reviews
   - Update store information

## Technical Architecture

### Backend
- **Framework**: Laravel (PHP)
- **Database**: MySQL
- **Authentication**: Laravel Breeze

### Key Models
- User (with role-based permissions)
- Product (with variants)
- Order (with order items)
- Vendor
- Wishlist
- Cart
- Category
- Coupon
- Blog

### API Structure
- RESTful API design
- Route grouping by user roles
- Middleware for authentication and authorization

## Installation

1. Clone the repository
   ```bash
   git clone https://github.com/Moo50Atia/Laravel-Ecommerce
   ```

2. Install dependencies
   ```bash
   composer install
   npm install
   ```

3. Set up environment variables
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure database in .env file

5. Run migrations and seeders
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. Compile assets
   ```bash
   npm run dev
   ```

7. Start the server
   ```bash
   php artisan serve
   ```

## License

This project is licensed under the MIT License - see the LICENSE file for details.
