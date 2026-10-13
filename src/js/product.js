/* src/js/product.js */
export function initProductPage() {
    // 1. LÓGICA DA GALERIA MISTA (FOTOS E VÍDEOS)
    const mainMedias = document.querySelectorAll('.main-media'); // Pega todas as mídias da esquerda
    const thumbs = document.querySelectorAll('.thumb-img');
    const dots = document.querySelectorAll('.dot');
    const arrowLeft = document.querySelector('.arrow-left');
    const arrowRight = document.querySelector('.arrow-right');
    
    let currentIndex = 0;

    function updateGallery(index) {
        currentIndex = index;
        
        // Esconde TODAS as mídias principais e pausa os vídeos
        mainMedias.forEach(media => {
            media.classList.remove('active');
            if (media.tagName === 'VIDEO') {
                media.pause();
            }
        });
        
        // Mostra apenas a mídia atual
        if (mainMedias[currentIndex]) {
            mainMedias[currentIndex].classList.add('active');
            // Se for vídeo, dá play automático ao aparecer
            if (mainMedias[currentIndex].tagName === 'VIDEO') {
                mainMedias[currentIndex].play();
            }
        }

        // Atualiza as bordinhas nas miniaturas (Desktop)
        thumbs.forEach(t => t.classList.remove('active'));
        if(thumbs[currentIndex]) thumbs[currentIndex].classList.add('active');

        // Atualiza as bolinhas (Mobile)
        dots.forEach(d => d.classList.remove('active'));
        if(dots[currentIndex]) dots[currentIndex].classList.add('active');
    }

    // Adiciona o clique nas miniaturas (Desktop)
    thumbs.forEach((thumb, idx) => {
        thumb.addEventListener('click', () => updateGallery(idx));
    });

    // Adiciona o clique nas bolinhas (Mobile)
    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => updateGallery(idx));
    });

    // Setas esquerda/direita
    if (arrowLeft) {
        arrowLeft.addEventListener('click', () => {
            let newIndex = currentIndex - 1;
            if (newIndex < 0) newIndex = mainMedias.length - 1;
            updateGallery(newIndex);
        });
    }

    if (arrowRight) {
        arrowRight.addEventListener('click', () => {
            let newIndex = currentIndex + 1;
            if (newIndex >= mainMedias.length) newIndex = 0;
            updateGallery(newIndex);
        });
    }

    // 2. LÓGICA DAS CORES E ESTOQUE
    const colorCircles = document.querySelectorAll('.color-circle');
    const colorTextLabel = document.getElementById('selected-color-text');
    const stockTextLabel = document.getElementById('stock-text');
    const buyButton = document.getElementById('buy-button');

    colorCircles.forEach(circle => {
        circle.addEventListener('click', () => {
            // Remove a seleção de todos os círculos
            colorCircles.forEach(c => c.classList.remove('active'));
            
            // Pega a cor e o estoque do círculo clicado
            const selectedColor = circle.getAttribute('data-color');
            const stock = parseInt(circle.getAttribute('data-stock')); 
            
            // Ativa os círculos corretos (sincroniza Desktop e Mobile)
            document.querySelectorAll(`.color-circle[data-color="${selectedColor}"]`).forEach(c => {
                c.classList.add('active');
            });
            
            // Atualiza o texto da cor
            if (colorTextLabel) colorTextLabel.textContent = selectedColor;
            
            // Lógica do Estoque e bloqueio do botão Comprar
            if (stockTextLabel && buyButton) {
                if (stock > 0) {
                    stockTextLabel.textContent = `Em estoque: ${stock} unidades`;
                    stockTextLabel.className = 'stock-info'; 
                    buyButton.textContent = 'Comprar';
                    buyButton.style.opacity = '1';
                    buyButton.style.pointerEvents = 'auto';
                } else {
                    stockTextLabel.textContent = 'Esgotado';
                    stockTextLabel.className = 'stock-info out'; 
                    buyButton.textContent = 'Indisponível';
                    buyButton.style.opacity = '0.5';
                    buyButton.style.pointerEvents = 'none'; 
                }
            }
        });
    });

    // 3. LÓGICA DO ACORDEON (Detalhes do Produto)
    const accHeaders = document.querySelectorAll('.acc-header');

    accHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('span');
            
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
                icon.textContent = "add";
            } else {
                document.querySelectorAll('.acc-content').forEach(c => c.style.maxHeight = null);
                document.querySelectorAll('.acc-header span').forEach(i => i.textContent = "add");
                
                content.style.maxHeight = content.scrollHeight + "px";
                icon.textContent = "remove";
            }
        });
    });
}

// Inicia o script
initProductPage();