import { initNavbar } from './navbar.js';
import { initHeroInteractions } from './hero.js';
import { initCartAnimation } from './cart.js'; 
import { initFooter } from './footer.js';
import { initProductPage } from './product.js';

// Função para carregar o AOS dinamicamente no navegador (Evita erros de módulo no XAMPP)
function loadAOS() {
    // 1. Carrega o CSS do AOS
    const aosCSS = document.createElement('link');
    aosCSS.rel = 'stylesheet';
    aosCSS.href = 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css';
    document.head.appendChild(aosCSS);

    // 2. Carrega o JS do AOS
    const aosScript = document.createElement('script');
    aosScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js';
    
    // Quando o script terminar de carregar, inicializa as animações
    aosScript.onload = () => {
        if (window.AOS) {
            window.AOS.init({
                duration: 1000, 
                easing: 'ease-out-cubic', 
                once: true, 
                offset: 50, 
            });
        }
    };
    document.body.appendChild(aosScript);
}

document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializa os seus módulos
    initNavbar();
    initHeroInteractions();
    initCartAnimation();
    initFooter();
    initProductPage();
    
    // 2. Carrega e inicializa o AOS de forma segura
    loadAOS();

    // =========================================
    //  SISTEMA DE PERFORMANCE DO VÍDEO HERO
    // =========================================
    const heroVideo = document.querySelector('.hero-video-wrapper');
    if (heroVideo) {
        window.addEventListener('scroll', () => {
            // Se rolar para baixo mais do que a altura da tela
            if (window.scrollY > window.innerHeight) {
                heroVideo.style.display = 'none'; // Esconde e tira o vídeo da memória visual
            } else {
                heroVideo.style.display = 'block'; // Traz o vídeo de volta ao subir
            }
        });
    }

    // =========================================
    //  BOTÃO FLUTUANTE (BACK TO TOP)
    // =========================================
    const topBtn = document.getElementById("backToTop");

    if (topBtn) {
        window.addEventListener("scroll", () => {
            // Mostra o botão após rolar 400px (um pouco mais que 1 tela)
            if (window.scrollY > 400) {
                topBtn.classList.add("show");
            } else {
                topBtn.classList.remove("show");
            }
        });

        topBtn.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }
});