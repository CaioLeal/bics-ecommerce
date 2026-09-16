export function initNavbar() {
    const navbar = document.getElementById('navbar');
    const menuBtn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-btn');
    const sidebar = document.getElementById('sidebar-menu');
    const overlay = document.getElementById('sidebar-overlay');

    // =========================================
    //   EFEITO SCROLL (TRANSPARENTE PARA BRANCO)
    // =========================================
    window.addEventListener('scroll', () => {
        // Se rolar mais que 50 pixels pra baixo
        if (window.scrollY > 50) {
            navbar.classList.remove('transparent');
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.add('transparent');
            navbar.classList.remove('scrolled');
        }
    });

    // =========================================
    //   ABRIR / FECHAR MENU LATERAL
    // =========================================
    function toggleMenu() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        
        // Bloqueia o scroll do site por trás quando o menu está aberto
        if (sidebar.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    menuBtn.addEventListener('click', toggleMenu);
    closeBtn.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu); // Fecha clicando fora
}