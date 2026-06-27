import type { Config } from "tailwindcss";

const config: Config = {
  darkMode: ["class"],
  content: ["./src/**/*.{ts,tsx}"],
  theme: {
    extend: {
      colors: {
        brand: {
          50: "#eef6ff",
          100: "#d8e9ff",
          500: "#1967d2",
          700: "#11489a",
          900: "#0c2e5f"
        }
      }
    }
  },
  plugins: []
};

export default config;
