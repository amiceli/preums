import "./app.ui"
import { createInertiaApp } from "@inertiajs/vue3"
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers"
import VNetworkGraph from "v-network-graph"
import type { DefineComponent } from "vue"
import { createApp, h } from "vue"
import "v-network-graph/lib/style.css"

const appName = import.meta.env.VITE_APP_NAME || "Laravel"

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>("./pages/**/*.vue")),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(VNetworkGraph)
            .mount(el)
    },
    progress: {
        color: "#4B5563",
        showSpinner: true,
    },
})
