import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  root: __dirname,
  base: '/assets/',
  build: {
    outDir: path.resolve(__dirname, '../public/assets'),
    emptyOutDir: false,
    rollupOptions: {
      input: {
        editor: path.resolve(__dirname, 'js/editor-js.js'),
        styles: path.resolve(__dirname, 'css/style.css'),
      },
      output: {
        entryFileNames: 'js/[name].js',
        assetFileNames: (info) => {
          const name = info.name || '';
          const ext = name.split('.').pop();
          if (ext === 'css') return 'css/[name][extname]';
          if (/(png|jpe?g|gif|svg|webp|avif)$/i.test(ext)) return 'img/[name][extname]';
          if (/(woff2?|ttf|otf|eot)$/i.test(ext)) return 'fonts/[name][extname]';
          return 'misc/[name][extname]';
        },
        manualChunks: undefined,
      },
    },
  },
});
