import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import routes from "@/router/routes.js";

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        return savedPosition || { top: 0 };
    },
});

router.beforeEach((to, from, next) => {
    const { isAuthenticated } = useAuthStore();

    const privateRoute = to.matched.some((record) => !record.meta.public);
    const publicRoute = to.matched.some((record) => record.meta.public);

    if (privateRoute && !isAuthenticated) next("/login");
    else if (publicRoute && isAuthenticated) next("/");
    else next();
});

export default router;
