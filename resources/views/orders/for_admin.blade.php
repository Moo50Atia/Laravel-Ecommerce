{{-- <div class="container">
<h2>orders List</h2>
<a href="{{ route('vendor.orders.create') }}" class="btn btn-primary mb-3">Create orders</a>
<table class="table">
    <thead>
        <tr><th>user_id</th><th>vendor_id</th><th>order_number</th><th>status</th><th>total_amount</th><th>discount_amount</th><th>shipping_amount</th><th>grand_total</th><th>payment_method</th><th>payment_status</th><th>shipping_address</th><th>billing_address</th><th>notes</th></tr>
    </thead>
    <tbody>
        @foreach ($orders as $item)
                <tr>
                    <td>{{$item->user_id}}</td>
<td>{{$item->vendor_id}}</td>
<td>{{$item->order_number}}</td>
<td>{{$item->status}}</td>
<td>{{$item->total_amount}}</td>
<td>{{$item->discount_amount}}</td>
<td>{{$item->shipping_amount}}</td>
<td>{{$item->grand_total}}</td>
<td>{{$item->payment_method}}</td>
<td>{{$item->payment_status}}</td>
<td>{{$item->shipping_address}}</td>
<td>{{$item->billing_address}}</td>
<td>{{$item->notes}}</td>
<td>
                        <a href="{{ route('vendor.orders.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('vendor.orders.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div> --}}
@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-2 sm:mb-0">Orders List</h2>
        <a href="{{ route('vendor.orders.create') }}" 
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Create Order
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-2">User ID</th>
                    <th class="px-4 py-2">Vendor ID</th>
                    <th class="px-4 py-2">Order #</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2">Discount</th>
                    <th class="px-4 py-2">Shipping</th>
                    <th class="px-4 py-2">Grand Total</th>
                    <th class="px-4 py-2">Payment Method</th>
                    <th class="px-4 py-2">Payment Status</th>
                    <th class="px-4 py-2">Shipping Address</th>
                    <th class="px-4 py-2">Billing Address</th>
                    <th class="px-4 py-2">Notes</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $item->user_id }}</td>
                    <td class="px-4 py-2">{{ $item->vendor_id }}</td>
                    <td class="px-4 py-2">{{ $item->order_number }}</td>
                    <td class="px-4 py-2">{{ $item->status }}</td>
                    <td class="px-4 py-2">{{ $item->total_amount }}</td>
                    <td class="px-4 py-2">{{ $item->discount_amount }}</td>
                    <td class="px-4 py-2">{{ $item->shipping_amount }}</td>
                    <td class="px-4 py-2">{{ $item->grand_total }}</td>
                    <td class="px-4 py-2">{{ $item->payment_method }}</td>
                    <td class="px-4 py-2">{{ $item->payment_status }}</td>
                    <td class="px-4 py-2">{{ $item->shipping_address }}</td>
                    <td class="px-4 py-2">{{ $item->billing_address }}</td>
                    <td class="px-4 py-2">{{ $item->notes }}</td>
                    <td class="px-4 py-2 flex flex-col sm:flex-row sm:gap-2">
                        <a href="{{ route('vendor.orders.edit', $item->id) }}" 
                           class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition text-center mb-2 sm:mb-0">
                            Edit
                        </a>
                        <form action="{{ route('vendor.orders.destroy', $item->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure?')" class="text-center">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition w-full">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection