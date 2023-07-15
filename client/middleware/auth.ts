export default defineNuxtRouteMiddleware((to, from) => {
    const { jwt, deleteJWT } = useAuth();
    const isLoggedIn = computed(() => !!jwt.value);

    if (!isLoggedIn.value && to.path !== "/login") {
        deleteJWT();
        return navigateTo("/login");
    }

    if (!process.client && to.path !== "/login") {
        deleteJWT();
        return navigateTo("/login");
    }

    if (
        (jwt.value === undefined || jwt.value === null) &&
        to.path === "/login"
    ) {
        return;
    }

    const base64Url = jwt.value.split(".")[1];
    const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
    const decodedToken = JSON.parse(
        decodeURIComponent(escape(window.atob(base64))),
    );
    const expireDate = new Date(decodedToken.exp * 1000);
    const now = new Date();
    const isTokenValid = now < expireDate;

    if (!isTokenValid) {
        deleteJWT();
        return navigateTo("/login");
    }

    if (to.path === "/login") {
        return navigateTo("/");
    }
});
