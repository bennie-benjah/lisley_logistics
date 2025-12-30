import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin/admin-core.js',
                'resources/js/admin/services.js',
                'resources/js/admin/customers.js',
                'resources/js/admin/products.js',
                'resources/js/admin/shipments.js',
                'resources/js/admin/inventory.js',
                'resources/js/admin/orders.js'
            ],
            refresh: true,
        }),
    ],
});
