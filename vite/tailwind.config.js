/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    '../public/**/*.php',
    '../app/**/*.php',
    './**/*.{html,js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {
      /* =========================
       * Couleurs du thème
       * ========================= */
      colors: {
        // Tokens principaux (issus de :root)
        primary:   "var(--primary)",     // #002343c9 (peut contenir alpha)
        secondary: "var(--secondary)",   // #FF6600
        accent:    "var(--accent1)",     // #00C9A7
        background:"var(--background)",  // #0D0D0D
        text:      "var(--text)",        // #fff
        textDark:  "var(--text-on-dark)",// #FFFFFF
        cardBorder:"var(--border-card)", // #FF6600
        highlight: "var(--highlight)",   // #FFD700
        muted:     "#999",
        surface:   "#1a1a1a",
        surface2:  "#1e1e1e",
        line:      "#333",
        light:     "#f2f2f2",
        soft:      "#e0e0e0",
        danger:    "#e74c3c",
        dangerDark:"#c0392b",
        violet:    "#9D4EDD",
        // Optionnel : synonymes utiles
        brand:     "var(--secondary)",   // alias pour 'secondary'
        success:   "var(--accent1)",     // alias pour 'accent'
        warning:   "var(--highlight)",
      },

      /* =========================
       * Police
       * ========================= */
      fontFamily: {
        mono: ["Fira Code", "ui-monospace", "SFMono-Regular", "Menlo", "monospace"],
        // Si tu veux une sans-serif secondaire :
        // sans: ["Inter", "system-ui", "Arial", "sans-serif"],
      },

      /* =========================
       * Rayons / Ombres
       * ========================= */
      borderRadius: {
        // utiles pour cartes/boutons
        card: "0.75rem",
        pill: "9999px",
      },
      boxShadow: {
        // Glow doré (réseaux sociaux / boutons)
        "glow-gold": "0 4px 8px rgba(255,215,0,0.3)",
        // Lueur accent
        "glow-accent": "0 6px 14px rgba(0,201,167,0.35)",
        // Lueur orange
        "glow-brand": "0 6px 14px rgba(255,102,0,0.35)",
      },

      /* =========================
       * Fonds / Dégradés
       * ========================= */
      backgroundImage: {
        "dark-gradient":
          "linear-gradient(135deg, #1a1a1a 0%, #0D0D0D 100%)",
      },

      /* =========================
       * Largeurs max / Container
       * ========================= */
      maxWidth: {
        "content": "1200px", // vu dans ton CSS pour le wrapper
      },

      /* =========================
       * Animations (reprend tes keyframes)
       * ========================= */
      keyframes: {
        glowHueShift: {
          "0%":   { filter: "hue-rotate(0deg)" },
          "50%":  { filter: "hue-rotate(180deg)" },
          "100%": { filter: "hue-rotate(360deg)" },
        },
        floatWave: {
          "0%":   { transform: "translateY(0)" },
          "50%":  { transform: "translateY(-6px)" },
          "100%": { transform: "translateY(0)" },
        },
        floatParticle: {
          "0%":   { transform: "translateY(0) translateX(0)" },
          "50%":  { transform: "translateY(-8px) translateX(4px)" },
          "100%": { transform: "translateY(0) translateX(0)" },
        },
      },
      animation: {
        glowHueShift: "glowHueShift 15s ease-in-out infinite",
        floatWave: "floatWave 6s ease-in-out infinite",
        floatParticle: "floatParticle 8s ease-in-out infinite",
      },
    },
  },
  plugins: [
    require('preline/plugin'),
  ]
};
