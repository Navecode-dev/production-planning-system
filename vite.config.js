import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: [
               'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true, // For hot reloading in development
        }),
        // Add viteStaticCopy plugin to copy necessary JS files to public/build/assets
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/jquery/dist/jquery.min.js',
                    dest: 'assets' // Copies to public/build/assets/jquery.min.js
                },
                {
                    src: 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
                    dest: 'assets' // Copies to public/build/assets/bootstrap.bundle.min.js
                },
                {
                    src: 'node_modules/admin-lte/dist/js/adminlte.min.js',
                    dest: 'assets' // Copies to public/build/assets/adminlte.min.js
                },
                {
                    src: 'node_modules/datatables.net/js/jquery.dataTables.min.js',
                    dest: 'assets' // Copies to public/build/assets/jquery.dataTables.min.js
                },
                {
                    src: 'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
                    dest: 'assets' // Copies to public/build/assets/dataTables.bootstrap4.min.js
                },
                {
                    src: 'node_modules/datatables.net-responsive/js/dataTables.responsive.min.js',
                    dest: 'assets' // Copies to public/build/assets/dataTables.responsive.min.js
                },
                {
                    src: 'node_modules/moment/min/moment-with-locales.min.js',
                    dest: 'assets' // Copies to public/build/assets/moment-with-locales.min.js
                },
            ],
        }),
    ],
});
