// js/glide.js
import Glide from '@glidejs/glide';
import '@glidejs/glide/dist/css/glide.core.min.css';
// Optionnel : thème par défaut
import '@glidejs/glide/dist/css/glide.theme.min.css';
import { Breakpoints } from '@glidejs/glide/dist/glide.modular.esm.js';

// Configuration de base
const config = {
    type: 'carousel', // ou 'slider'
    startAt: 0,
    perView: 3,
    gap: 20,
    autoplay: 3000, // ms ou false pour désactiver
    hoverpause: true,
    breakpoints: {
        1280: { perView: 3, gap: 20 },
        1024: { perView: 2, gap: 16 },
        768:  { perView: 1, gap: 12 },
        480:  { perView: 1, gap: 8, peek: { before: 0, after: 0 } },
    }
};

// Initialisation quand les articles sont prêt
window.initGlide = () => {
    const sliders = document.querySelectorAll('.glide');
    
    sliders.forEach((sliderEl, index) => {
        const glide = new Glide(sliderEl, config);
        glide.mount();
        console.log(`Slider ${index + 1} initialisé`);
    });
}

// Initialisation quand le DOM est prêt
// document.addEventListener('DOMContentLoaded', () => {
//     const sliders = document.querySelectorAll('.glide');
    
//     sliders.forEach((sliderEl, index) => {
//         const glide = new Glide(sliderEl, config);
//         glide.mount();
//         console.log(`Slider ${index + 1} initialisé`);
//     });
// });
