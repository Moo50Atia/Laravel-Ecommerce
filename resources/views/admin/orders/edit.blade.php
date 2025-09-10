<x-app-layout>
    <x-slot name="style">
        <style>
            .form-card {
                transition: all 0.3s ease;
            }
            .form-card:hover {
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            .status-badge {
                transition: all 0.3s ease;
            }
            .status-badge:hover {
                transform: scale(1.05);
            }
        </style>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8" data-aos="fade-down">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">تعديل الطلب</h1>
                    <p class="text-gray-600">تعديل معلومات الطلب: {{ $order->order_number }}</p>
                </div>
                <a href="{{ route('admin.orders.show', $order) }}" 
                   class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    العودة للطلب
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="max-w-4xl mx-auto">
            <div class="form-card bg-white rounded-lg shadow-md p-8" data-aos="fade-up">
                <!-- Errors Display -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6" data-aos="fade-up">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">يرجى تصحيح الأخطاء التالية:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Order Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-aos="fade-up" data-aos-delay="100">
                        <!-- Order Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                حالة الطلب <span class="text-red-500">*</span>
                            </label>
                            <select id="status" 
                                    name="status" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>معلق</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
                                <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>ملغي</option>
                                <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>مسترد</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Status -->
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">
                                حالة الدفع <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_status" 
                                    name="payment_status" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>مدفوع</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>فشل</option>
                            </select>
                            @error('payment_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Financial Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="200">
                        <!-- Total Amount -->
                        <div>
                            <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                إجمالي المنتجات
                            </label>
                            <input type="number" 
                                   id="total_amount" 
                                   name="total_amount" 
                                   value="{{ old('total_amount', $order->total_amount) }}" 
                                   step="0.01"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="0.00">
                            @error('total_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Discount Amount -->
                        <div>
                            <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                مبلغ الخصم
                            </label>
                            <input type="number" 
                                   id="discount_amount" 
                                   name="discount_amount" 
                                   value="{{ old('discount_amount', $order->discount_amount) }}" 
                                   step="0.01"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="0.00">
                            @error('discount_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Shipping Amount -->
                        <div>
                            <label for="shipping_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                مبلغ الشحن
                            </label>
                            <input type="number" 
                                   id="shipping_amount" 
                                   name="shipping_amount" 
                                   value="{{ old('shipping_amount', $order->shipping_amount) }}" 
                                   step="0.01"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="0.00">
                            @error('shipping_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Grand Total (Read-only) -->
                    <div data-aos="fade-up" data-aos-delay="300">
                        <label for="grand_total" class="block text-sm font-medium text-gray-700 mb-2">
                            الإجمالي النهائي
                        </label>
                        <input type="text" 
                               id="grand_total" 
                               value="{{ number_format($order->grand_total, 2) }} ريال" 
                               readonly
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 font-medium">
                        <p class="mt-1 text-sm text-gray-500">سيتم حساب الإجمالي تلقائياً</p>
                    </div>

                    <!-- Payment Method -->
                    <div data-aos="fade-up" data-aos-delay="400">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            طريقة الدفع
                        </label>
                        <select id="payment_method" 
                                name="payment_method" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">اختر طريقة الدفع</option>
                            <option value="credit_card" {{ $order->payment_method == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                            <option value="cod" {{ $order->payment_method == 'cod' ? 'selected' : '' }}>الدفع عند الاستلام</option>
                            <option value="bank_transfer" {{ $order->payment_method == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div data-aos="fade-up" data-aos-delay="500">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                  placeholder="أضف ملاحظات حول الطلب">{{ old('notes', $order->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-gray-50 rounded-lg p-6" data-aos="fade-up" data-aos-delay="600">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">ملخص الطلب</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span class="text-gray-700">رقم الطلب: {{ $order->order_number }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-700">تاريخ الإنشاء: {{ $order->created_at->format('Y-m-d') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-gray-700">العميل: {{ $order->user->name ?? 'غير محدد' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="text-gray-700">المنتجات: {{ $order->items->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-4 pt-6" data-aos="fade-up" data-aos-delay="700">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            تحديث الطلب
                        </button>
                        <a href="{{ route('admin.orders.show', $order) }}" 
                           class="flex-1 bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            // Auto-calculate grand total
            function calculateGrandTotal() {
                const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
                const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
                const shippingAmount = parseFloat(document.getElementById('shipping_amount').value) || 0;
                
                const grandTotal = totalAmount - discountAmount + shippingAmount;
                document.getElementById('grand_total').value = grandTotal.toFixed(2) + ' ريال';
            }

            // Event listeners for auto-calculation
            document.getElementById('total_amount').addEventListener('input', calculateGrandTotal);
            document.getElementById('discount_amount').addEventListener('input', calculateGrandTotal);
            document.getElementById('shipping_amount').addEventListener('input', calculateGrandTotal);

            // Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                const status = document.getElementById('status').value;
                const paymentStatus = document.getElementById('payment_status').value;
                
                if (!status || !paymentStatus) {
                    e.preventDefault();
                    alert('يرجى ملء جميع الحقول المطلوبة');
                }
            });

            // Status change notification
            document.getElementById('status').addEventListener('change', function() {
                const status = this.value;
                const statusText = this.options[this.selectedIndex].text;
                
                if (status === 'delivered') {
                    if (confirm('هل أنت متأكد من تغيير حالة الطلب إلى "تم التوصيل"؟')) {
                        console.log('Order status changed to delivered');
                    } else {
                        this.value = '{{ $order->status }}';
                    }
                }
            });
        </script>
    </x-slot>
</x-app-layout>
