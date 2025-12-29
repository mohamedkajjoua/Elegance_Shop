/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: "#5F2EEA",
        "primary-dark": "#4a24b8",
        secondary: "#1A1A1A",
        background: "#F8F9FA",
        "card-bg": "#FFFFFF",
        text: "#1A1A1A",
        "text-light": "#A0A3BD",
        border: "#E4E4E4",
        danger: "#FF4757",
        success: "#34C759",
        warning: "#FF9F43",
      },
      fontFamily: {
        sans: ["Outfit", "sans-serif"],
      },
      boxShadow: {
        sm: "0 2px 8px rgba(0, 0, 0, 0.05)",
        md: "0 8px 24px rgba(0, 0, 0, 0.08)",
      },
    },
  },
  plugins: [],
};
