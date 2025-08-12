import 'preline';

// certains setups nécessitent l’auto-init manuelle
window.addEventListener('DOMContentLoaded', () => {
    window.HSStaticMethods?.autoInit();
    console.log('Preline initialisé');
});