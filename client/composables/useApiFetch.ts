export const useApiFetch = (path, opts) => {
  const xsrfToken = useCookie("XSRF-TOKEN");
  return useFetch(path, {
    baseURL: "http://localhost:8000",
    mode: "cors",
    credentials: "include",
    headers: {
      "X-XSRF-TOKEN": xsrfToken,
      Accept: "application/json",
    },
    ...opts,
  });
};
