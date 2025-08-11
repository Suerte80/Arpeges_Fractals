// bs-config.cjs
module.exports = {
    proxy: "https://arpegesfractals.local", // ou http://arpegesfractals.local si tu restes en HTTP
    open: false,           // n’ouvre pas automatiquement un onglet
    port: 3000,            // BrowserSync écoutera ici (UI: 3001)
    ui: { port: 3001 },
    https: false,          // laisse BS en HTTP; il proxie quand même ton site HTTPS Caddy
    files: [
        "**/*.php",
        "public/assets/**/*.css",
        "public/assets/**/*.js"
    ],
    // évite d’écouter node_modules et compagnie
    ignore: [
        "node_modules/**",
        "public/assets/**.map"
    ],
    notify: true,          // petit toast de reload, utile en dev
    ghostMode: false
};
