import "./bootstrap";
import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";
import { useAuthStore } from "./stores/auth";
import Notifications from "@kyvg/vue3-notification";

const app = createApp(App);
app.use(createPinia());
const { getUser } = useAuthStore();

getUser().then(() => {
    app.use(router);
    app.use(Notifications);
    app.mount("#app");
});
