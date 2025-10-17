document.addEventListener('DOMContentLoaded', async () => {
    const shw = document.getElementById('carousel');
    const wrapper = document.getElementById('carousel-wrapper');
    let items = [];
    let currentIndex = 0;
    let isAnimating = false;

    const response = await fetch('http://localhost:8131/lasfm/top15artists');
    const data = await response.json();

    shw.classList.remove('no');
    
    data.forEach(artist => {
        const item = document.createElement('div');
        item.className = 'carousel-item';
        item.innerHTML = `
            <img src="${artist.images}" alt="${artist.name}">
            <div class="highlight-info">
                <h1>${artist.name}</h1>
                <p>Playcount: ${artist.playcount}</p>
                <p>Listeners: ${artist.listeners}</p>
                <button onclick="window.open('${artist.url}', '_blank')">ABRIR NO SPOTIFY</button>
            </div>
        `;
        wrapper.appendChild(item);
        items.push(item);
    });

    function updateCarousel() {
        const total = items.length;
        items.forEach((item, index) => {
            item.classList.remove('position-left-3','position-left-2','position-left-1','position-center','position-right-1','position-right-2','position-right-3','position-far-left','position-far-right');
            
            let pos = index - currentIndex;
            
            if (pos > total / 2) pos -= total;
            else if (pos < -total / 2) pos += total;

            if (pos === 0) item.classList.add('position-center');
            
            // Posições à Esquerda (1, 2, 3)
            else if (pos === -1 || pos === total - 1) item.classList.add('position-left-1');
            else if (pos === -2 || pos === total - 2) item.classList.add('position-left-2');
            else if (pos === -3 || pos === total - 3) item.classList.add('position-left-3');
            
            // Posições à Direita (1, 2, 3)
            else if (pos === 1 || pos === -(total - 1)) item.classList.add('position-right-1');
            else if (pos === 2 || pos === -(total - 2)) item.classList.add('position-right-2');
            else if (pos === 3 || pos === -(total - 3)) item.classList.add('position-right-3');

            // Itens que estão muito longe (fora do range de -3 a 3)
            else if (pos < -3) item.classList.add('position-far-left'); // Atualiza a condição
            else item.classList.add('position-far-right'); // Atualiza a condição
        });
    }

    function goToIndex(newIndex) {
        if (isAnimating) return;
        isAnimating = true;
        const incoming = items[newIndex];
        incoming.classList.add('move-to-front');
        void incoming.offsetWidth;

        currentIndex = newIndex;
        updateCarousel();

        incoming.addEventListener('transitionend', () => {
            incoming.classList.remove('move-to-front');
            isAnimating = false;
        }, { once: true });
    }

    window.nextItem = () => goToIndex((currentIndex + 1) % items.length);
    window.prevItem = () => goToIndex((currentIndex - 1 + items.length) % items.length);

    items.forEach((item, index) => {
        item.addEventListener('click', () => {
            if (index !== currentIndex) goToIndex(index);
        });
    });

    updateCarousel();
});