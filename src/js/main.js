import AOS from 'aos';
import 'aos/dist/aos.css';
import { initNavbar } from './navbar.js';
import { initCartAnimation } from './cart.js';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializa a Navbar (que vai voltar a ficar branca no scroll!)
    initNavbar();
    initCartAnimation();
    
    // 2. Inicializa o AOS para as animações
    AOS.init({
        duration: 1000, 
        easing: 'ease-out-cubic', 
        once: true, 
        offset: 50, 
    });
});