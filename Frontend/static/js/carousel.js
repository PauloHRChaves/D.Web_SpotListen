document.addEventListener('DOMContentLoaded', async () => {
    
    // =======================================================
    // 1. LÓGICA DO CARROSSEL (TOP ARTISTS)
    // =======================================================
    
    const shw = document.getElementById('carousel');
    const wrapper = document.getElementById('carousel-wrapper');
    let items = [];
    let currentIndex = 0;
    let isAnimating = false;
    
    try {
        const responseArtists = await fetch('https://spotlisten-api.loca.lt/lasfm/top15artists');
        const dataArtists = await responseArtists.json();

        shw.classList.remove('no');
        
        dataArtists.forEach(artist => {
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

        // Funções do Carrossel (Definidas AQUI DENTRO da função principal)
        function updateCarousel() {
            const total = items.length;
            items.forEach((item, index) => {
                // ... (toda a sua lógica de classes de posição)
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
                else if (pos < -3) item.classList.add('position-far-left');
                else item.classList.add('position-far-right');
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
        
        // Expor funções globais para os botões HTML (next/prev)
        window.nextItem = () => goToIndex((currentIndex + 1) % items.length);
        window.prevItem = () => goToIndex((currentIndex - 1 + items.length) % items.length);

        items.forEach((item, index) => {
            item.addEventListener('click', () => {
                if (index !== currentIndex) goToIndex(index);
            });
        });

        updateCarousel();
        
    } catch (error) {
        console.error("Erro ao carregar top artists:", error);
        // Opcional: Esconder a seção do carrossel em caso de falha
        shw.style.display = 'none'; 
    }
    
    // =======================================================
    // 2. LÓGICA DA LISTA DE MÚSICAS (TOP TRACKS)
    // =======================================================
    
    const wrapperTracks = document.getElementById('track-list-wrapper');
    const sectionTracks = document.getElementById('top-tracks-section');

    try {
        const responseTracks = await fetch('https://spotlisten-api.loca.lt/lasfm/top15tracks');
        const dataTracks = await responseTracks.json();

        sectionTracks.classList.remove('no');
        
        // Função de formatação para Playcount e Listeners
        const formatNumber = (num) => {
            if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
            if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
            return num;
        };

        dataTracks.forEach((track, index) => {
            const item = document.createElement('div');
            item.className = 'track-item';
            
            item.innerHTML = `
                <div class="track-header">
                    <img src="${track.image}" class="track-item-image" alt="Capa da faixa ${track.name}">
                    <div class="track-info-main">
                        <h1>#${index + 1} - ${track.name}</h1>
                    </div>
                </div>
                <div class="track-metrics">
                    <div class="track-metric-item">Playcount: <span class="metric-value">${formatNumber(track.playcount)}</span></div>
                    <div class="track-metric-item">Listeners: <span class="metric-value">${formatNumber(track.listeners)}</span></div>
                    <div class="track-metric-item">Popularidade: <span class="metric-value">${track.popularity}%</span></div>
                </div>
                <button onclick="window.open('${track.url}', '_blank')">ABRIR NO SPOTIFY</button>
            `;
            wrapperTracks.appendChild(item);
        });
    } catch (error) {
        console.error("Erro ao carregar top tracks:", error);
        sectionTracks.style.display = 'none';
    }
});