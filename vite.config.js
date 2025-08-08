import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        commonjsOptions: {
            strictRequires: false,
        },
        rollupOptions: {
            output: {
                manualChunks: undefined,
            }
        }
    },
    optimizeDeps: {
        include: ['tailwindcss', 'postcss', 'autoprefixer']
    },
    esbuild: {
        target: 'es2022',
        supported: {
            'top-level-await': true
        }
    }
});
