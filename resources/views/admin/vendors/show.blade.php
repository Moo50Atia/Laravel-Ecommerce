<x-app-layout>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Vendor Header -->
        <div class="bg-white rounded-lg shadow-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $vendor->store_name }}</h2>
                        <p class="text-gray-600">{{ $vendor->description ?? 'No description provided' }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.vendors.edit', $vendor) }}"
                           class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Edit
                        </a>
                        <form action="{{ route('admin.vendors.destroy', $vendor) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                    onclick="return confirm('Are you sure you want to delete this vendor?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-700">Contact Information</h4>
                        <p class="text-gray-600">Email: {{ $vendor->email }}</p>
                        <p class="text-gray-600">Phone: {{ $vendor->phone }}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700">Owner</h4>
                        <p class="text-gray-600">{{ $vendor->user->name }}</p>
                        <p class="text-gray-600">{{ $vendor->user->email }}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700">Statistics</h4>
                        <p class="text-gray-600">Products: {{ $vendor->products->count() }}</p>
                        <p class="text-gray-600">Orders: {{ $vendor->orders->count() }}</p>
                        <p class="text-gray-600">Commission Rate: {{ $vendor->commission_rate }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="bg-white rounded-lg shadow-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700">Products ({{ $vendor->products->count() }})</h3>
            </div>
            <div class="px-6 py-4">
                @if($vendor->products->isEmpty())
                    <p class="text-gray-500">No products yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($vendor->products->take(5) as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($product->price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->total_stock }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $product->total_stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $product->total_stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($vendor->products->count() > 5)
                            <div class="mt-4">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">View all products →</a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Orders Section -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700">Recent Orders ({{ $vendor->orders->count() }})</h3>
            </div>
            <div class="px-6 py-4">
                @if($vendor->orders->isEmpty())
                    <p class="text-gray-500">No orders yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($vendor->orders->take(5) as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($order->grand_total, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($vendor->orders->count() > 5)
                            <div class="mt-4">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">View all orders →</a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>
