import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        outDir: 'dist', // Build dehors, jamais dans public !
        emptyOutDir: true,
        rollupOptions: {
            input: 'vite/editor-js.js'
        }
    },
});
