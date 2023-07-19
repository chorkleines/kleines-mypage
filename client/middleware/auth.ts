export default defineNuxtRouteMiddleware(async (to, from) => {
  const { data } = await useApiFetch("/api/auth", {
    method: "GET",
  });
  const isAuthenticated = data.value === "authenticated";

  if (!isAuthenticated && to.path !== "/login") {
    return navigateTo("/login");
  }

  if (!process.client && to.path !== "/login") {
    return navigateTo("/login");
  }

  if (isAuthenticated && to.path === "/login") {
    return navigateTo("/");
  }
});
