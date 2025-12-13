/**
 * Order Management JavaScript
 * Handles order listing, filtering, and management functionality
 */

class OrderManager {
    constructor() {
        this.initEventListeners();
        this.setupAutoRefresh();
    }

    initEventListeners() {
        // Auto-submit form when filters change
        this.setupFilterAutoSubmit();
        
        // Auto-submit search with delay
        this.setupSearchAutoSubmit();
        
        // Add loading state to form submission
        this.setupFormLoadingState();
        
        // Setup export functionality
        this.setupExportFunctionality();
    }

    setupFilterAutoSubmit() {
        const filterSelects = document.querySelectorAll('select[name="status"], select[name="payment_method"], select[name="payment_status"]');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    }

    setupSearchAutoSubmit() {
        const searchInput = document.querySelector('input[name="search"]');
        let searchTimeout;
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.closest('form').submit();
                }, 500); // 500ms delay
            });
        }
    }

    setupFormLoadingState() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>جاري التطبيق...';
                    submitBtn.disabled = true;
                }
                
                // Add loading overlay
                const loadingOverlay = document.createElement('div');
                loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                loadingOverlay.innerHTML = '<div class="bg-white p-4 rounded-lg"><div class="flex items-center"><svg class="animate-spin h-6 w-6 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>جاري تحميل النتائج...</div></div>';
                document.body.appendChild(loadingOverlay);
            });
        }
    }

    setupExportFunctionality() {
        // Make exportOrders function globally available
        window.exportOrders = this.exportOrders.bind(this);
    }

    exportOrders() {
        // Implement export functionality
        alert('سيتم تصدير البيانات قريباً');
    }

    setupAutoRefresh() {
        // Auto-refresh every 30 seconds to show latest data
        setInterval(function() {
            // You can implement AJAX refresh here if needed
        }, 30000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new OrderManager();
});
