import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',      // ← Dockerから外部アクセスを許可
        port: 5173,           // ← 明示的にViteポート指定
        cors: true,
        hmr: {
            host: '127.0.0.1', // ← Windowsブラウザから見えるホスト
        },
    },
});
