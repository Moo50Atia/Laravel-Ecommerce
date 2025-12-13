# Repository Pattern Implementation

This document explains how to use the repository pattern implementation in your Laravel ecommerce project.

## Overview

The repository pattern has been implemented for the high-priority models:
- **ProductRepository** - Complex product queries and filtering
- **OrderRepository** - Order management and business logic
- **UserRepository** - User management with role-based access
- **VendorRepository** - Vendor management and statistics

## Architecture

```
app/Repositories/
├── Contracts/
│   ├── RepositoryInterface.php          # Base repository interface
│   ├── ProductRepositoryInterface.php   # Product-specific methods
│   ├── OrderRepositoryInterface.php     # Order-specific methods
│   ├── UserRepositoryInterface.php      # User-specific methods
│   └── VendorRepositoryInterface.php    # Vendor-specific methods
├── BaseRepository.php                   # Base repository implementation
├── ProductRepository.php               # Product repository
├── OrderRepository.php                 # Order repository
├── UserRepository.php                  # User repository
└── VendorRepository.php                # Vendor repository
```

## Usage Examples

### 1. Product Repository

#### Basic Usage
```php
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        // Get products for admin with filtering
        $products = $this->productRepository->getForAdmin(Auth::user(), [
            'search' => $request->get('search'),
            'category_id' => $request->get('category_id'),
            'per_page' => 15
        ]);

        // Get product statistics
        $statistics = $this->productRepository->getAdminStatistics(Auth::user());

        return view('admin.products.index', compact('products', 'statistics'));
    }
}
```

#### Available Methods
```php
// Get top rated products
$topProducts = $this->productRepository->getTopRated(5);

// Search products
$searchResults = $this->productRepository->search('laptop');

// Get products by category
$categoryProducts = $this->productRepository->getByCategory(1);

// Get products by vendor
$vendorProducts = $this->productRepository->getByVendor(1);

// Get products with stock
$inStockProducts = $this->productRepository->getInStock();

// Get products by price range
$priceRangeProducts = $this->productRepository->getByPriceRange(100, 500);
```

### 2. Order Repository

#### Basic Usage
```php
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        // Get orders for admin with filtering
        $orders = $this->orderRepository->getForAdmin(Auth::user(), [
            'status' => $request->get('status'),
            'payment_status' => $request->get('payment_status'),
            'date_from' => $request->get('date_from'),
            'per_page' => 15
        ]);

        // Get dashboard data
        $dashboardData = $this->orderRepository->getDashboardData(Auth::user());

        return view('admin.orders.index', compact('orders', 'dashboardData'));
    }
}
```

#### Available Methods
```php
// Get orders by user
$userOrders = $this->orderRepository->getByUser(1);

// Get orders by status
$pendingOrders = $this->orderRepository->getByStatus('pending');

// Get orders by payment status
$paidOrders = $this->orderRepository->getByPaymentStatus('paid');

// Get recent orders
$recentOrders = $this->orderRepository->getRecent(10);

// Update order status
$this->orderRepository->updateStatus(1, 'delivered');

// Get total sales
$totalSales = $this->orderRepository->getTotalSales();
```

### 3. User Repository

#### Basic Usage
```php
use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        // Get users for admin with filtering
        $users = $this->userRepository->getForAdmin([
            'role' => $request->get('role'),
            'status' => $request->get('status'),
            'search' => $request->get('search'),
            'per_page' => 15
        ]);

        // Get user statistics
        $statistics = $this->userRepository->getStatistics();

        return view('admin.users.index', compact('users', 'statistics'));
    }
}
```

#### Available Methods
```php
// Get users by role
$customers = $this->userRepository->getCustomers();
$vendors = $this->userRepository->getVendors();
$admins = $this->userRepository->getAdmins();

// Get users by status
$activeUsers = $this->userRepository->getActive();
$inactiveUsers = $this->userRepository->getInactive();

// Search users
$searchResults = $this->userRepository->search('john');

// Get users by city
$cityUsers = $this->userRepository->getByCity('New York');

// Get user count by role
$customerCount = $this->userRepository->getCountByRole('customer');
```

### 4. Vendor Repository

#### Basic Usage
```php
use App\Repositories\Contracts\VendorRepositoryInterface;

class VendorController extends Controller
{
    protected $vendorRepository;

    public function __construct(VendorRepositoryInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    public function index(Request $request)
    {
        // Get vendors for admin with filtering
        $vendors = $this->vendorRepository->getForAdmin([
            'search' => $request->get('search'),
            'min_rating' => $request->get('min_rating'),
            'has_products' => $request->get('has_products'),
            'per_page' => 15
        ]);

        // Get vendor statistics
        $statistics = $this->vendorRepository->getStatistics();

        return view('admin.vendors.index', compact('vendors', 'statistics'));
    }
}
```

#### Available Methods
```php
// Get top rated vendors
$topVendors = $this->vendorRepository->getTopRated(10);

// Get vendors with products
$vendorsWithProducts = $this->vendorRepository->getWithProducts();

// Get vendors by rating range
$highRatedVendors = $this->vendorRepository->getByRatingRange(4.0, 5.0);

// Search vendors
$searchResults = $this->vendorRepository->search('electronics');

// Get vendors with sales statistics
$vendorsWithSales = $this->vendorRepository->getWithTotalSales();

// Get vendors by commission rate
$highCommissionVendors = $this->vendorRepository->getHighCommissionVendors();
```

## Benefits of Using Repositories

### 1. **Separation of Concerns**
- Controllers focus on HTTP handling
- Repositories handle data access logic
- Models focus on business rules

### 2. **Testability**
- Easy to mock repositories for testing
- Isolated business logic testing
- Better unit test coverage

### 3. **Reusability**
- Same queries used across multiple controllers
- Centralized business logic
- Consistent data access patterns

### 4. **Maintainability**
- Changes to queries only require repository updates
- Easy to add new query methods
- Centralized filtering and sorting logic

### 5. **Performance**
- Centralized query optimization
- Easy to add caching
- Consistent eager loading strategies

## Migration from Direct Model Usage

### Before (Direct Model Usage)
```php
// In Controller
$products = Product::with(['vendor', 'category'])
    ->where('is_active', true)
    ->where('name', 'like', '%' . $search . '%')
    ->orderBy('created_at', 'desc')
    ->paginate(15);

$statistics = [
    'total' => Product::count(),
    'active' => Product::where('is_active', true)->count(),
];
```

### After (Repository Usage)
```php
// In Controller
$products = $this->productRepository->getForAdmin(Auth::user(), [
    'search' => $search,
    'per_page' => 15
]);

$statistics = $this->productRepository->getAdminStatistics(Auth::user());
```

## Service Provider Registration

The repositories are automatically registered in `AppServiceProvider`:

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    $this->app->bind(VendorRepositoryInterface::class, VendorRepository::class);
}
```

## Best Practices

1. **Always use interfaces** - Inject interfaces, not concrete classes
2. **Keep repositories focused** - One repository per model
3. **Use descriptive method names** - Clear intent and purpose
4. **Handle relationships properly** - Use eager loading appropriately
5. **Add proper filtering** - Support common filter patterns
6. **Return appropriate types** - Collections, Paginators, or single models
7. **Add documentation** - Document complex query methods

## Next Steps

1. **Update existing controllers** to use repositories
2. **Add caching** to frequently accessed data
3. **Create repository tests** for better coverage
4. **Add more specific methods** as needed
5. **Consider adding repository events** for logging

This implementation provides a solid foundation for maintainable and testable data access in your Laravel ecommerce application.
