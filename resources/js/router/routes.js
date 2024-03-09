const routes = [
    {
        path: "/login",
        name: "login",
        meta: { public: true },
        component: () => import("@/pages/Login.vue"),
    },
    {
        meta: { title: "Layout" },
        path: "/",
        name: "layout",
        redirect: "/",
        component: () => import("@/layouts/BaseLayout.vue"),
        children: [
            {
                meta: { title: "Dashboard", menu: "dashboard" },
                path: "/",
                name: "dashboard",
                component: () => import("@/pages/dashboard/Index.vue"),
            },
            {
                meta: { title: "Data Vaksin", menu: "dataVaksin" },
                path: "/data-vaksin",
                name: "dataVaksin",
                component: () => import("@/pages/dataVaksin/Index.vue"),
            },
            {
                meta: { title: "Peramalan", menu: "peramalan" },
                path: "/peramalan",
                name: "peramalan",
                component: () => import("@/pages/peramalan/Index.vue"),
            },
            {
                meta: { title: "Peramalan", menu: "peramalan" },
                path: "/peramalan/:id/detail",
                name: "detailPeramalan",
                component: () => import("@/pages/peramalan/Detail.vue"),
            },
            {
                meta: { title: "Peramalan", menu: "peramalan" },
                path: "/peramalan/preview",
                name: "previewPeramalan",
                component: () => import("@/pages/peramalan/Preview.vue"),
            },
            {
                meta: { title: "Setting", menu: "setting" },
                path: "/setting",
                name: "setting",
                component: () => import("@/pages/setting/Index.vue"),
            },
        ],
    },
    {
        name: "404",
        path: "/:catchAll(.*)",
        component: () => import("@/pages/404.vue"),
    },
];

export default routes;
