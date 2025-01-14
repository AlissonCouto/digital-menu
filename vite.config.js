import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/admin.css',
                'resources/css/app.css',
                'resources/css/cart.css',
                'resources/css/charts.css',
                'resources/css/login.css',
                'resources/css/orders.css',

                'resources/js/admin.js',
                'resources/js/app.js',
                'resources/js/cart.js',
                'resources/js/charts.js',
                'resources/js/edit-orders.js',
                'resources/js/orders.js',
                'resources/js/validate.js',
            ],
            refresh: true,
        }),
    ],
});
