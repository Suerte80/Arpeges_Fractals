import { defineConfig } from 'vite';
import path from 'path';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  // Le fichier est dans /vite, donc racine = ce dossier
  root: __dirname,
  base: '/assets/',
  build: {
    // Écrire dans ../public/assets (où Apache sert les fichiers)
    outDir: path.resolve(__dirname, '../public/assets'),
    emptyOutDir: false,
    rollupOptions: {
      input: {
        editor: path.resolve(__dirname, 'js/editor-js.js'),
        styles: path.resolve(__dirname, 'css/style.css')
      },
      output: {
        entryFileNames: 'js/[name].js', // JS dans public/assets/js
        assetFileNames: (chunkInfo) => {
            const name = chunkInfo.name || '';
            const ext = name.split('.').pop();
            if (ext === 'css') return 'css/[name][extname]';
            if (/(png|jpe?g|gif|svg|webp|avif)$/.test(ext)) return 'img/[name][extname]';
            if (/(woff2?|ttf|otf|eot)$/.test(ext)) return 'fonts/[name][extname]';
            return 'misc/[name][extname]';
        },
        manualChunks: undefined
      }
    }
  },
  plugins: [
    tailwindcss(),
  ]
});
