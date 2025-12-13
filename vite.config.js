import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/admin/orders.js',
                'resources/js/admin/order-edit.js'
            ],
            refresh: true,
        }),
    ],
});
