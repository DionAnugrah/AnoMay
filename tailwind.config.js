/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        extend: {
            colors: {
                "anomay-orange": "#ff7f11",
                "anomay-dark": "#1e293b",
            },
            fontFamily: {
                inter: ["Inter", "sans-serif"],
                poppins: ["Poppins", "sans-serif"],
            },
        },
    },
    plugins: [],
};
