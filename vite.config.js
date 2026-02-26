import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // // Cargar vite en la IP local para que sea accesible desde otros dispositivos en la misma red
    // server: {
    //     host: '192.168.1.67', 
    //     hmr: {
    //         host: '192.168.1.67',
    //     },
    // },
});
