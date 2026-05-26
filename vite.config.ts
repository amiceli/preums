import { wayfinder } from "@laravel/vite-plugin-wayfinder"
import vue from "@vitejs/plugin-vue"
import laravel from "laravel-vite-plugin"
import { defineConfig } from "vite"

export default defineConfig({
    optimizeDeps: {
        exclude: ["v-network-graph"],
    },

    server: {
        host: "0.0.0.0",
        port: 5173,
        hmr: {
            host: process.env.VITE_HMR_HOST || "localhost",
        },
        watch: {
            usePolling: true,
        },
    },

    plugins: [
        laravel({
            input: ["resources/js/app.ts"],
            ssr: "resources/js/ssr.ts",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
                compilerOptions: {
                    isCustomElement: (tag: string) => tag.startsWith(`wa-`),
                },
            },
        }),
        wayfinder({
            formVariants: true,
        }),
    ],
})
