export function initHeroInteractions() {
    const heroVideo = document.querySelector('.hero-video-wrapper');
    const scrollIndicator = document.getElementById('scroll-indicator');
    const mainContent = document.querySelector('.main-content');
    const navbar = document.getElementById('navbar');

    // 1. Clique na setinha deslizando para a loja
    if (scrollIndicator && mainContent) {
        scrollIndicator.addEventListener('click', () => {
            // Calcula a altura da Navbar para descontar e a seção não ficar escondida embaixo dela
            const navbarHeight = navbar ? navbar.offsetHeight : 80;
            
            // Pega a posição exata do topo do Main Content na tela inteira
            const targetPosition = mainContent.getBoundingClientRect().top + window.scrollY - navbarHeight;

            // Faz o scroll macio
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        });
    }

    // 2. Ouvinte de rolagem (Scroll) para otimização e fade da seta
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;

        // Desliga o vídeo quando passar da tela para poupar bateria
        if (heroVideo) {
            if (scrollY > window.innerHeight) {
                heroVideo.style.display = 'none'; 
            } else {
                heroVideo.style.display = 'block'; 
            }
        }

        // Esconde a setinha quando o usuário começa a rolar a tela (Fade Out)
        if (scrollIndicator) {
            if (scrollY > 100) {
                scrollIndicator.style.opacity = '0';
                scrollIndicator.style.pointerEvents = 'none'; // Evita clique invisível
            } else {
                scrollIndicator.style.opacity = '.7';
                scrollIndicator.style.pointerEvents = 'auto';
            }
        }
    });
}