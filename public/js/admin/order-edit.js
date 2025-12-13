/**
 * Order Edit JavaScript
 * Handles order editing functionality including calculations and validations
 */

class OrderEditManager {
    constructor() {
        this.initEventListeners();
        this.calculateGrandTotal();
    }

    initEventListeners() {
        // Auto-calculate grand total when amounts change
        this.setupAmountCalculations();
        
        // Form validation
        this.setupFormValidation();
        
        // Status change notifications
        this.setupStatusChangeNotifications();
    }

    setupAmountCalculations() {
        const amountInputs = ['total_amount', 'discount_amount', 'shipping_amount'];
        
        amountInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', () => this.calculateGrandTotal());
            }
        });
    }

    calculateGrandTotal() {
        const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
        const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
        const shippingAmount = parseFloat(document.getElementById('shipping_amount').value) || 0;
        
        const grandTotal = totalAmount - discountAmount + shippingAmount;
        const grandTotalElement = document.getElementById('grand_total');
        
        if (grandTotalElement) {
            grandTotalElement.value = grandTotal.toFixed(2) + ' ريال';
        }
    }

    setupFormValidation() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', (e) => {
                const status = document.getElementById('status').value;
                const paymentStatus = document.getElementById('payment_status').value;
                
                if (!status || !paymentStatus) {
                    e.preventDefault();
                    alert('يرجى ملء جميع الحقول المطلوبة');
                    return false;
                }
            });
        }
    }

    setupStatusChangeNotifications() {
        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            statusSelect.addEventListener('change', (e) => {
                const status = e.target.value;
                const statusText = e.target.options[e.target.selectedIndex].text;
                
                if (status === 'delivered') {
                    if (confirm('هل أنت متأكد من تغيير حالة الطلب إلى "تم التوصيل"؟')) {
                        console.log('Order status changed to delivered');
                    } else {
                        // Reset to original value
                        const originalStatus = statusSelect.dataset.originalStatus;
                        if (originalStatus) {
                            e.target.value = originalStatus;
                        }
                    }
                }
            });
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new OrderEditManager();
});
