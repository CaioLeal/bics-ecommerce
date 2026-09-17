export function initCartAnimation() {
    const cartBtns = document.querySelectorAll('.btn-cart');
    const navbarCart = document.getElementById('cart-icon');

    if (!navbarCart) return;

    cartBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault(); 

            const productCard = btn.closest('.product-card');
            const productImg = productCard.querySelector('.product-img-primary');

            const flyingImg = productImg.cloneNode(true);
            const imgRect = productImg.getBoundingClientRect();
            const cartRect = navbarCart.getBoundingClientRect();

            flyingImg.style.position = 'fixed';
            flyingImg.style.top = `${imgRect.top}px`;
            flyingImg.style.left = `${imgRect.left}px`;
            flyingImg.style.width = `${imgRect.width}px`;
            flyingImg.style.height = `${imgRect.height}px`;
            flyingImg.style.borderRadius = '12px';
            flyingImg.style.zIndex = '9999';
            flyingImg.style.pointerEvents = 'none'; 
            
            // MUDANÇA 1: Tempo aumentado para 1.5s para um voo mais lento e luxuoso
            flyingImg.style.transition = 'all 1.5s cubic-bezier(.25, .46, .45, .94)';

            document.body.appendChild(flyingImg);

            requestAnimationFrame(() => {
                flyingImg.style.top = `${cartRect.top + 5}px`;
                flyingImg.style.left = `${cartRect.left + 5}px`;
                flyingImg.style.width = '20px';
                flyingImg.style.height = '20px';
                flyingImg.style.opacity = '0';
                flyingImg.style.borderRadius = '50%';
            });

            // MUDANÇA 2: O timer de remoção precisa bater com os 1500ms
            setTimeout(() => {
                flyingImg.remove();
                
                navbarCart.style.transition = 'transform .2s ease';
                navbarCart.style.transform = 'scale(1.3)';
                
                setTimeout(() => {
                    navbarCart.style.transform = 'scale(1)';
                }, 200);

            }, 1500); 
        });
    });
}