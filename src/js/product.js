/* src/js/product.js */
export function initProductPage() {
    // 1. LÓGICA DA GALERIA DE IMAGENS
    const mainImg = document.getElementById('main-product-image');
    const thumbs = document.querySelectorAll('.thumb-img');
    const dots = document.querySelectorAll('.dot');
    const arrowLeft = document.querySelector('.arrow-left');
    const arrowRight = document.querySelector('.arrow-right');
    
    // Array com as fontes das imagens (pegando dos thumbnails)
    const images = Array.from(thumbs).map(t => t.src);
    let currentIndex = 0;

    function updateGallery(index) {
        currentIndex = index;
        
        // Troca a imagem com efeito suave de opacidade
        mainImg.style.opacity = 0;
        setTimeout(() => {
            mainImg.src = images[currentIndex];
            mainImg.style.opacity = 1;
        }, 150);

        // Atualiza estilo dos thumbnails
        thumbs.forEach(t => t.classList.remove('active'));
        if(thumbs[currentIndex]) thumbs[currentIndex].classList.add('active');

        // Atualiza bolinhas mobile
        dots.forEach(d => d.classList.remove('active'));
        if(dots[currentIndex]) dots[currentIndex].classList.add('active');
    }

    // Clique nos thumbnails
    thumbs.forEach((thumb, idx) => {
        thumb.addEventListener('click', () => updateGallery(idx));
    });

    // Clique nas setas
    if(arrowLeft) arrowLeft.addEventListener('click', () => {
        let newIndex = currentIndex - 1;
        if (newIndex < 0) newIndex = images.length - 1;
        updateGallery(newIndex);
    });

    if(arrowRight) arrowRight.addEventListener('click', () => {
        let newIndex = currentIndex + 1;
        if (newIndex >= images.length) newIndex = 0;
        updateGallery(newIndex);
    });

    // Clique nas bolinhas (Mobile)
    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => updateGallery(idx));
    });

    // 2. LÓGICA DAS CORES
    const colorCircles = document.querySelectorAll('.color-circle');
    const colorTextLabel = document.getElementById('selected-color-text');

    colorCircles.forEach(circle => {
        circle.addEventListener('click', () => {
            // Remove active de todos e bota no clicado
            colorCircles.forEach(c => c.classList.remove('active'));
            
            // Procura todos os círculos com a mesma cor (para sincronizar desktop e mobile)
            const selectedColor = circle.getAttribute('data-color');
            document.querySelectorAll(`.color-circle[data-color="${selectedColor}"]`).forEach(c => {
                c.classList.add('active');
            });
            
            // Atualiza o texto da cor no desktop
            if(colorTextLabel) colorTextLabel.textContent = selectedColor;
        });
    });

    // 3. LÓGICA DO ACORDEON DE DETALHES
    const accHeaders = document.querySelectorAll('.acc-header');

    accHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('span');
            
            // Se já tá aberto, fecha
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
                icon.textContent = "add";
            } else {
                // Fecha todos os outros antes de abrir
                document.querySelectorAll('.acc-content').forEach(c => c.style.maxHeight = null);
                document.querySelectorAll('.acc-header span').forEach(i => i.textContent = "add");
                
                // Abre o clicado
                content.style.maxHeight = content.scrollHeight + "px";
                icon.textContent = "remove";
            }
        });
    });
}

// Inicializa automaticamente se o arquivo for carregado
initProductPage();