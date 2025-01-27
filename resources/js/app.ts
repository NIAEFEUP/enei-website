import "../css/app.css";

import { createApp, h, type DefineComponent } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createVfm } from "vue-final-modal";
import VueCookies from "vue-cookies";
// @ts-expect-error: Ziggy does not have type declarations
import { ZiggyVue } from "ziggy";
import { addIcons } from "oh-vue-icons";
import { createI18n } from "vue-i18n";
import config from "./localization";

const vfm = createVfm();

import * as IoIcons from "oh-vue-icons/icons/io";
import { VueQrcodeReader } from "vue-qrcode-reader";

const Io = Object.values({ ...IoIcons });
addIcons(
    ...Io.map((i) => ({
        ...i,
        raw: i.raw.replace('stroke="#000"', 'stroke="currentColor"'),
    })),
);

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

const i18n = createI18n(config);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>("./Pages/**/*.vue"),
        ),
    setup: ({ el, App, props, plugin }) => {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vfm)
            .use(i18n)
            .use(ZiggyVue, Ziggy)
            .use(VueCookies, { expires: "7d" })
            .use(VueQrcodeReader)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
