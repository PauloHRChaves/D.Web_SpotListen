document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.carousel-item');
    const totalItems = items.length;
    let currentIndex = 0;
    let isAnimating = false;

    const updateCarousel = () => {
        items.forEach((item, index) => {

            item.classList.remove('position-left-2', 'position-left-1', 'position-center', 'position-right-1', 'position-right-2', 'position-far-left', 'position-far-right');

            let position = index - currentIndex;

            if (position > totalItems / 2) {
                position -= totalItems;
            } else if (position < -totalItems / 2) {
                position += totalItems;
            }

            if (position === 0) {
                item.classList.add('position-center');
                // Se a diferença for 0, o item é o centro.
            } else if (position === -1 || position === totalItems - 1) {
                item.classList.add('position-left-1');
                // Se a diferença for -1 ( o primeiro à esquerda )
            } else if (position === 1 || position === -(totalItems - 1)) {
                item.classList.add('position-right-1');
                // Se a diferença for +1 ( o primeiro à direita )
            } else if (position === -2 || position === totalItems - 2) {
                item.classList.add('position-left-2');
                // Segundo à esquerda.
            } else if (position === 2 || position === -(totalItems - 2)) {
                item.classList.add('position-right-2');
                // Segundo à direita.
            } else if (position < -2 || position > 2) {
                // Qualquer outro item que não seja centro, -1, 1, -2 ou 2.
                if (position < 0) {
                    item.classList.add('position-far-left');
                    // Se o item estiver muito à esquerda (fora da vista principal).
                } else {
                    item.classList.add('position-far-right');
                    // Se o item estiver muito à direita (fora da vista principal).
                }
            }
        });
    };

    const goToBook = (newIndex) => {
        if (isAnimating) {
            return;
        }
        isAnimating = true;

        // item que irá para o centro
        const incomingItem = items[newIndex];
        
        // Adiciona classe de z-index
        incomingItem.classList.add('move-to-front');
        
        // Força o navegador a renderizar a mudança de classe antes da transição
        void incomingItem.offsetWidth;

        // Atualiza o índice global e o carrossel
        currentIndex = newIndex;
        updateCarousel();

        // Remove a classe de z-index e libera a 'trava' no fim da transição
        incomingItem.addEventListener('transitionend', () => {
            incomingItem.classList.remove('move-to-front');
            isAnimating = false;
        }, { once: true });
    };
    
    const nextBook = () => {
        const newIndex = (currentIndex + 1) % totalItems;
        goToBook(newIndex);
    };
    
    const prevBook = () => {
        const newIndex = (currentIndex - 1 + totalItems) % totalItems;
        goToBook(newIndex);
    };
    
    // Centraliza o livro clicado
    items.forEach((item, index) => {
        item.addEventListener('click', () => {
            if (index === currentIndex) {
                return;
            }
            goToBook(index);
        });
    });

    window.nextBook = nextBook;
    window.prevBook = prevBook;

    updateCarousel();
});