import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./vendor/laravel/jetstream/**/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.vue",
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ["Figtree", ...defaultTheme.fontFamily.sans],
        primary: ["Meiryo", "sans-serif"],
        secondary: ["Noto Sans JP", "sans-serif"],
      },
    },
    colors: {
      primary: "#18293D",
      white: "#ffffff",
      grayF4: "#F4F4F4",
      gray8A: "#8A8A8A",
      grayB8: "#B8B8B8",
      grayF0F2: "#F0F2F5",
      grayE2: "#E2E2E2",
      gray59: "#595A5C",
      grayE9: "#E9E9E9",
      grayC1: "#C1C1C1",
      grayB0: "#B0B0B0",
      grayF5: "#F5F5F5",
      black54: "#545454",
      backBD: "#BDBDBD",
      black28: "#282828",
      black27: "#272727",
      black22: "#222222",
      black26: "#262626",
      black64: "#646464",
      black30: "#303133",
      black59: "#595959",
      redE2: "#E21124",
      redF3: "#F35B5B",
      redD1: "#D12030",
      green19: "#19AC28",
      green34: "#34C759",
      green2D: "#2DC87E",
      greenE5: "#E5F6E6",
      orangeFF: "#FF9900",
      orangeF1C: "#FFA41C",
      black00: "#000000",
      grayFE: "#FEFEFE",
      wclabel: "#ff521c",
    },
  },

  plugins: [forms, typography],
};
