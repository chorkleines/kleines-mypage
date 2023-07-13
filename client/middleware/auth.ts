export default defineNuxtRouteMiddleware((to, from) => {
    const token = useCookie("jwt");
    const isLoggedIn = computed(() => !!token.value);

    if (!isLoggedIn.value) {
        return navigateTo("/login");
    }

    if (!process.client) {
        return navigateTo("/login");
    }

    const base64Url = token.value.split(".")[1];
    const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
    const decodedToken = JSON.parse(
        decodeURIComponent(escape(window.atob(base64))),
    );
    const expireDate = new Date(decodedToken.exp * 1000);
    const now = new Date();
    const isTokenValid = now < expireDate;

    if (!isTokenValid) {
        token.value = null;
        return navigateTo("/login");
    }
});
