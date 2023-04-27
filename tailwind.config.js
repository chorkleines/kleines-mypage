/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./index.html", "./resources/js/**/*.{vue,js,ts,jsx,tsx}"],
    theme: {
        extend: {},
    },
    plugins: [require("daisyui")],
};
