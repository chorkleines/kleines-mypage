// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    runtimeConfig: {
        public: {
            baseURL: "http://localhost:8000",
        },
    },
    ssr: false,
    modules: ["@nuxtjs/tailwindcss"],
    css: [
        "@/assets/styles/app.scss",
        "@fortawesome/fontawesome-svg-core/styles.css",
        "@/assets/styles/vue-good-table.scss",
    ],
    plugins: ["@/plugins/fontawesome.ts"],
    buildModules: ["@nuxt/google-fonts"],
    googleFonts: {
        families: {
            NotoSansJP: [400, 500, 800],
        },
    },
});
