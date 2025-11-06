import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // 👇 Include every entry point you actually use
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/staff.js',
            ],
            refresh: true,
        }),
        // 👇 Enables Tailwind features (jit, nesting, etc.)
        tailwindcss(),
    ],
    build: {
        // 👇 Vite puts hashed files here (Laravel looks in config/vite.php)
        outDir: 'public/build',
        manifest: true,
        emptyOutDir: true,
    },
    server: {
        // 👇 So `npm run dev` still works locally
        host: 'localhost',
        port: 5173,
        hmr: { host: 'localhost' },
    },
});
