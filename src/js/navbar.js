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

    // Elementos do Modal de Registro
    const openRegisterBtn = document.getElementById('open-register-btn');
    const leftRegisterBtn = document.getElementById('left-register-btn'); // NOVO BOTAO ESQUERDO
    const registerModal = document.getElementById('register-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');

    // =========================================
    //   EFEITO SCROLL (TRANSPARENTE PARA BRANCO)
    // =========================================
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.remove('transparent');
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.add('transparent');
            navbar.classList.remove('scrolled');
        }
    });

    // =========================================
    //   FUNÇÃO GERAL PARA ABRIR PAINÉIS
    // =========================================
    function toggleSidebar(sidebarElement) {
        if (sidebarElement.classList.contains('active')) {
            sidebarElement.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            sidebarMenu.classList.remove('active');
            sidebarLogin.classList.remove('active');
            if(registerModal) registerModal.classList.remove('active');
            
            sidebarElement.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden'; 
        }
    }

    // Função para o Modal de Cadastro
    function toggleModal() {
        if (registerModal.classList.contains('active')) {
            registerModal.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            // Fecha a Sidebar de Login antes de abrir o modal
            sidebarLogin.classList.remove('active'); 
            
            registerModal.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    // Cliques nos Menus e Modal
    if(menuBtn) menuBtn.addEventListener('click', () => toggleSidebar(sidebarMenu));
    if(closeMenuBtn) closeMenuBtn.addEventListener('click', () => toggleSidebar(sidebarMenu));

    if(loginBtn) loginBtn.addEventListener('click', () => toggleSidebar(sidebarLogin));
    if(closeLoginBtn) closeLoginBtn.addEventListener('click', () => toggleSidebar(sidebarLogin));

    if(openRegisterBtn) openRegisterBtn.addEventListener('click', toggleModal);
    if(leftRegisterBtn) leftRegisterBtn.addEventListener('click', toggleModal); // NOVO CLIQUE
    if(closeModalBtn) closeModalBtn.addEventListener('click', toggleModal);

    // Fechar clicando no fundo escuro
    if(overlay) {
        overlay.addEventListener('click', () => {
            sidebarMenu.classList.remove('active');
            sidebarLogin.classList.remove('active');
            if(registerModal) registerModal.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // =========================================
    //   MOSTRAR/OCULTAR SENHA (GLOBAL)
    // =========================================
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault(); // Impede qualquer recarregamento acidental
            
            // Acha a div "input-group" e pega o input que está lá dentro (não falha nunca!)
            const inputGroup = btn.closest('.input-group');
            const input = inputGroup.querySelector('input');
            const icon = btn.querySelector('span');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility_off';
            }
        });
    });

    // =========================================
    //   MÁSCARA DE TELEFONE (+55 (00) 00000-0000)
    // =========================================
    const phoneInput = document.getElementById('reg-telefone');
    if(phoneInput) {
        phoneInput.addEventListener('input', (e) => {
            // Remove tudo que não é número
            let value = e.target.value.replace(/\D/g, "");
            
            // Vai montando a string dinamicamente
            if (value.length > 0) value = "+" + value;
            if (value.length > 3) value = value.replace(/^(\+\d{2})(\d)/, "$1 ($2");
            if (value.length > 7) value = value.replace(/(\(\d{2})(\d)/, "$1) $2");
            if (value.length > 13) value = value.replace(/(\d{5})(\d)/, "$1-$2");
            
            // Limita o tamanho para o formato perfeito
            e.target.value = value.substring(0, 19);
        });
    }
}