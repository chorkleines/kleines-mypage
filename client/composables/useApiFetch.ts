export const useApiFetch = (path, opts) => {
  const xsrfToken = useCookie("XSRF-TOKEN");
  return useFetch(path, {
    baseURL:
      process.env.NODE_ENV == "development"
        ? "http://localhost:8000"
        : "https://mypage.chorkleines.com/api/public",
    mode: "cors",
    credentials: "include",
    headers: {
      "X-XSRF-TOKEN": xsrfToken,
      Accept: "application/json",
    },
    ...opts,
  });
};
