export default defineNuxtRouteMiddleware(async (to, from) => {
  const { data } = await useApiFetch("/api/auth", {
    method: "GET",
  });
  const isAuthenticated = data.value.authenticated;

  const guestRoutes = ["/login", "/forgot-password", "/reset-password"];

  const isGuestRoute = guestRoutes.some((route) => to.path.startsWith(route));

  if (!isAuthenticated && !isGuestRoute) {
    return navigateTo("/login");
  }

  if (!process.client && !isGuestRoute) {
    return navigateTo("/login");
  }

  if (isAuthenticated && isGuestRoute) {
    return navigateTo("/");
  }
});
