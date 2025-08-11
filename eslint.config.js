import js from "@eslint/js";
import globals from "globals";
import json from "@eslint/json";
import markdown from "@eslint/markdown";
import css from "@eslint/css";
import { defineConfig } from "eslint/config";
import tailwind from "eslint-plugin-tailwindcss";
import htmlPlugin from "@html-eslint/eslint-plugin";
import htmlParser from "@html-eslint/parser";

export default defineConfig([
  { files: ["**/*.{js,mjs,cjs}"], plugins: { js }, extends: ["js/recommended"], languageOptions: { globals: globals.browser } },
  { files: ["**/*.json"], plugins: { json }, language: "json/json", extends: ["json/recommended"] },
  { files: ["**/*.jsonc"], plugins: { json }, language: "json/jsonc", extends: ["json/recommended"] },
  { files: ["**/*.md"], plugins: { markdown }, language: "markdown/commonmark", extends: ["markdown/recommended"] },
  { files: ["**/*.css"], plugins: { css }, language: "css/css", extends: ["css/recommended"] },
  {
    files: ["vite/js/**/*.{js,jsx,ts,tsx}", "public/**/*.html"], // adapte selon où sont tes classes
    languageOptions: {
      globals: globals.browser,
    },
    plugins: {
      tailwindcss: tailwind,
    },
    rules: {
      "tailwindcss/classnames-order": "warn",
      "tailwindcss/no-contradicting-classname": "warn",
      "tailwindcss/no-custom-classname": "off" // pratique si tu as des classes perso
    },
    settings: {
      tailwindcss: {
        // ton fichier Tailwind
        config: "vite/tailwind.config.js",
        // fonctions utilitaires qui reçoivent des classnames (si tu en as)
        callees: ["cn", "classnames"],
      },
    },
  },
  // HTML : parseur + plugin HTML, et règles Tailwind activées
  {
    files: ["public/**/*.html", "**/*.html"], // adapte aux chemins
    languageOptions: { parser: htmlParser },
    plugins: {
      "@html-eslint": htmlPlugin,
      tailwindcss: tailwind
    },
    rules: {
      // (rules HTML optionnelles)
      "@html-eslint/no-duplicate-id": "warn",

      // Tailwind dans le HTML :
      "tailwindcss/classnames-order": "warn",
      "tailwindcss/no-contradicting-classname": "warn"
    },
    settings: {
      tailwindcss: {
        config: "vite/tailwind.config.js"
      }
    }
  }
]);
