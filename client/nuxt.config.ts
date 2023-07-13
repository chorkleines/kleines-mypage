// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    runtimeConfig: {
        public: {
            baseURL: "http://localhost:8000",
        },
    },
    ssr: false,
});
