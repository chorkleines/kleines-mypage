import { createRouter, createWebHistory } from "vue-router";
import ExampleComponent from "./components/ExampleComponent.vue";
import Login from "./pages/auth/Login.vue";
import Home from "./pages/Home.vue";
import AuthApiService from "@/services/AuthApiService";
import LogoutResponse from "@/types/AuthType";

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
        meta: { requiresAuth: true },
    },
    { path: "/login", name: "login", component: Login },
    {
        path: "/logout",
        name: "logout",
        component: {
            beforeRouteEnter(to, from, next) {
                AuthApiService.logout()
                    .then((response: LogoutResponse) => {
                        localStorage.removeItem("jwt");
                        next("/login");
                    })
                    .catch((e: Error) => {
                        console.log(e);
                    });
            },
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to) => {
    if (to.name === "login" && localStorage.getItem("jwt") !== null) {
        return { name: "home" };
    }
    if (to.meta.requiresAuth && localStorage.getItem("jwt") == null) {
        return { name: "login" };
    }
});

export default router;
