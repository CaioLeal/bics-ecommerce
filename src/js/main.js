import AOS from 'aos';
import 'aos/dist/aos.css';
import { initNavbar } from './navbar.js';
import { initCartAnimation } from './cart.js'; // Mantido do seu código original
import { initFooter } from './footer.js';
import { initProductPage } from './product.js';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializa a Navbar (agora apenas UMA vez!)
    initNavbar();
    initCartAnimation();
    initFooter();
    initProductPage()
    
    // 2. Inicializa o AOS para as animações
    AOS.init({
        duration: 1000, 
        easing: 'ease-out-cubic', 
        once: true, 
        offset: 50, 
    });

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

        // Rola suavemente para o topo ao clicar (Como estamos usando o Lenis,
        // é bom usar o scrollTo da janela nativo que o Lenis intercepta)
        topBtn.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }
});
