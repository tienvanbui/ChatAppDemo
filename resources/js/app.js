import "./bootstrap";
import "element-plus/dist/index.css";
import "../css/app.css";
import "remixicon/fonts/remixicon.css";

import "../scss/main-bar-left.scss";
import "../scss/chat-side-bar.scss";
import "../scss/chat-footer.scss";

import { createApp, h } from "vue";
import { createPinia } from "pinia";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { Link } from "@inertiajs/vue3";
import ElementPlus from "element-plus";

const appName = import.meta.env.VITE_APP_NAME || "T-Chat";
const pinia = createPinia();

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob("./Pages/**/*.vue")
    ),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(ElementPlus)
      .use(pinia)
      .component("Link", Link)
      .mount(el);
  }
});
