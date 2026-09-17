/* src/js/navbar.js */
export function initNavbar() {
    const navbar = document.getElementById('navbar');
    const overlay = document.getElementById('sidebar-overlay');
    
    // Elementos do Menu Esquerdo
    const menuBtn = document.getElementById('menu-btn');
    const closeMenuBtn = document.getElementById('close-btn');
    const sidebarMenu = document.getElementById('sidebar-menu');

    // Elementos do Login Direito
    const loginBtn = document.getElementById('login-btn');
    const closeLoginBtn = document.getElementById('close-login-btn');
    const sidebarLogin = document.getElementById('login-sidebar');

    // Verifica se os elementos principais existem antes de rodar o código (Evita erros no console)
    if (!navbar || !sidebarMenu || !sidebarLogin) {
        console.error("Navbar: Elementos essenciais não encontrados.");
        return;
    }

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
    //   FUNÇÃO GERAL PARA ABRIR QUALQUER SIDEBAR
    // =========================================
    function toggleSidebar(sidebarElement) {
        // Se a sidebar alvo JÁ ESTÁ ativa, nós a fechamos (e o overlay junto)
        if (sidebarElement.classList.contains('active')) {
            sidebarElement.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        } 
        // Se ela está fechada, abrimos ela.
        else {
            // Antes de abrir, garante que a outra sidebar esteja fechada para não dar conflito!
            sidebarMenu.classList.remove('active');
            sidebarLogin.classList.remove('active');
            
            // Abre a sidebar selecionada
            sidebarElement.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Bloqueia scroll do site
        }
    }

    // =========================================
    //   EVENT LISTERNERS (CLIQUES)
    // =========================================

    // Cliques no Menu Esquerdo
    if(menuBtn) menuBtn.addEventListener('click', () => toggleSidebar(sidebarMenu));
    if(closeMenuBtn) closeMenuBtn.addEventListener('click', () => toggleSidebar(sidebarMenu));

    // Cliques no Login Direito
    if(loginBtn) loginBtn.addEventListener('click', () => toggleSidebar(sidebarLogin));
    if(closeLoginBtn) closeLoginBtn.addEventListener('click', () => toggleSidebar(sidebarLogin));

    // O clique no fundo escuro (overlay) fecha qualquer painel que estiver aberto
    if(overlay) {
        overlay.addEventListener('click', () => {
            sidebarMenu.classList.remove('active');
            sidebarLogin.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // =========================================
    //   MOSTRAR/OCULTAR SENHA (BÔNUS)
    // =========================================
    const togglePasswordBtn = document.querySelector('.toggle-password');
    const passwordInput = document.getElementById('login-senha');

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', () => {
            const icon = togglePasswordBtn.querySelector('span');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.textContent = 'visibility';
            } else {
                passwordInput.type = 'password';
                icon.textContent = 'visibility_off';
            }
        });
    }
}