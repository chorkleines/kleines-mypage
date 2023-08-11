/** @type {import('tailwindcss').Config} */
module.exports = {
  plugins: [require("daisyui"), require("@tailwindcss/typography")],
  theme: {
    extend: {
      height: {
        screen: ["100vh", "100dvh"],
      },
      minHeight: {
        screen: ["100vh", "100dvh"],
      },
      maxHeight: {
        screen: ["100vh", "100dvh"],
      },
    },
  },
};
