import { createRouter, createWebHistory } from "vue-router";
import Login from "./pages/auth/Login.vue";
import Home from "./pages/Home.vue";
import Users from "./pages/Users.vue";
import Accountings from "./pages/Accountings.vue";
import LogoutResponse from "@/types/AuthType";
import { useLogout } from "@/composable/auth/useLogout";
import type { NavigationGuardNext, RouteLocationNormalized } from "vue-router";

const { logout } = useLogout();

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
    },
    { path: "/login", name: "login", component: Login, meta: { guest: true } },
    {
        path: "/logout",
        name: "logout",
        component: {
            beforeRouteEnter(
                _to: RouteLocationNormalized,
                _from: RouteLocationNormalized,
                next: NavigationGuardNext
            ) {
                logout()
                    .then((_response: LogoutResponse) => {
                        localStorage.removeItem("jwt");
                        next("/login");
                    })
                    .catch((e: Error) => {
                        console.log(e);
                    });
            },
        },
    },
    { path: "/users", name: "users", component: Users },
    { path: "/accountings", name: "accountings", component: Accountings },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to) => {
    if (to.name === "login" && localStorage.getItem("jwt") !== null) {
        return { name: "home" };
    }
    if (!to.meta.guest) {
        const token = localStorage.getItem("jwt");
        if (!token) {
            return { name: "login" };
        }
        const base64Url = token.split(".")[1];
        const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
        const decodedToken = JSON.parse(
            decodeURIComponent(escape(window.atob(base64)))
        );
        const expireDate = new Date(decodedToken.exp * 1000);
        const now = new Date();
        const isValidToken = now < expireDate;
        if (isValidToken) {
            return;
        } else {
            localStorage.removeItem("jwt");
            return { name: "login" };
        }
    }
});

export default router;
