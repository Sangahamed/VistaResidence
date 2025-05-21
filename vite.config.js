import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/detail.js",
                "resources/js/userdashbord.js",
                "resources/js/notifications.js",
                'resources/js/map.js'
            ],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        include: ['alpinejs', 'leaflet']
    },
    define: {
        "import.meta.env.VITE_REVERB_APP_KEY": JSON.stringify(
            process.env.REVERB_APP_KEY
        ),
        "import.meta.env.VITE_REVERB_HOST": JSON.stringify(
            process.env.REVERB_HOST
        ),
        "import.meta.env.VITE_REVERB_PORT": JSON.stringify(
            process.env.REVERB_PORT
        ),
        "import.meta.env.VITE_REVERB_SCHEME": JSON.stringify(
            process.env.REVERB_SCHEME
        ),
    },
});
