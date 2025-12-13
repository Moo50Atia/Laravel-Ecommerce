/**
 * Admin Application JavaScript
 * Common functionality for admin panel
 */

class AdminApp {
    constructor() {
        this.init();
    }

    init() {
        this.setupCommonEventListeners();
        this.setupGlobalFunctions();
    }

    setupCommonEventListeners() {
        // Global form validation
        this.setupGlobalFormValidation();
        
        // Global loading states
        this.setupGlobalLoadingStates();
        
        // Global notifications
        this.setupGlobalNotifications();
    }

    setupGlobalFormValidation() {
        // Add CSRF token to all AJAX requests
        this.setupCSRFToken();
    }

    setupCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
        }
    }

    setupGlobalLoadingStates() {
        // Global loading overlay
        window.showLoading = function(message = 'جاري التحميل...') {
            const loadingOverlay = document.createElement('div');
            loadingOverlay.id = 'global-loading-overlay';
            loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            loadingOverlay.innerHTML = `
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center">
                        <svg class="animate-spin h-6 w-6 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>${message}</span>
                    </div>
                </div>
            `;
            document.body.appendChild(loadingOverlay);
        };

        window.hideLoading = function() {
            const loadingOverlay = document.getElementById('global-loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.remove();
            }
        };
    }

    setupGlobalNotifications() {
        // Global notification system
        window.showNotification = function(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        };
    }

    setupGlobalFunctions() {
        // Global utility functions
        window.formatCurrency = function(amount) {
            return new Intl.NumberFormat('ar-SA', {
                style: 'currency',
                currency: 'SAR'
            }).format(amount);
        };

        window.formatDate = function(date) {
            return new Intl.DateTimeFormat('ar-SA', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }).format(new Date(date));
        };
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new AdminApp();
});
