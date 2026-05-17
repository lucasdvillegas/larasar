import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { quasar, transformAssetUrls } from "@quasar/vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/tailwind.css",
                "resources/js/app.js"
            ],
            refresh: true,
        }),
        vue({
            template: { transformAssetUrls },
        }),
        quasar({
            autoImportComponentCase: "combined",
            sassVariables: "/resources/css/quasar-variables.sass",
        }),
    ],
});
