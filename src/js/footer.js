export function initFooter() {
    // Atualizar o ano do copyright automaticamente
    const anoAtualSpan = document.getElementById('current-year');
    if (anoAtualSpan) {
        anoAtualSpan.textContent = new Date().getFullYear();
    }

    // Sistema de Explosão Universal para os Botões
    function setupBadgeAnimation(selector) {
        const badge = document.querySelector(selector);
        
        if (badge) {
            badge.addEventListener('mouseenter', () => {
                if(badge.classList.contains('is-animating')) return;
                
                badge.classList.add('is-animating');
                
                // O seu timer de 4000ms intacto
                setTimeout(() => {
                    badge.classList.remove('is-animating');
                }, 4000); 
            });
        }
    }

    // Aciona a mágica para a Cosmos Agency
    setupBadgeAnimation('.cosmus-badge');
}