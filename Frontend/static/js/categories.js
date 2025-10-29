const params = new URLSearchParams(window.location.search);
let currentGenre = params.get('genre') || 'pop';
let currentSearchTerm = params.get('query') || '';
let currentOffset = 0;
const limit = 15;

const API_GENRE_URL = `http://127.0.0.1:8131/spotify/search/genre`;
const API_ARTIST_SEARCH_URL = `http://127.0.0.1:8131/spotify/search/artist`;

let container, prevButton, nextButton, pageInfo, tagsContainer, searchInput, searchButton;

function renderArtistCard(artist) {
    const DEFAULT_IMAGE = '/static/imgs/profile-icon.png';
    const imageUrl = artist.image_url || DEFAULT_IMAGE;
    const popularity = artist.popularity || 0;
    
    let badgeClass = 'normal';
    if (popularity >= 90) badgeClass = 'hot';
    else if (popularity >= 70) badgeClass = 'high';

    return `
        <div class="artist-card-wrapper" data-artist-id="${artist.id}">
            <div class="artist-card">
                <img src="${imageUrl}" alt="${artist.name}">
                <a href="${artist.profile_url}" target="_blank" class="card-overlay">
                    <i class="bi bi-play-circle-fill play-icon"></i>
                </a>
            </div>
            <div class="popularity-badge ${badgeClass}">
                <i class="bi bi-fire"></i> ${popularity}
            </div>
            <h2 class="artist-name">${artist.name}</h2>
        </div>
    `;
}

function updateContentTitle(title) {
    const titleElement = document.getElementById('current-genre-title');
    if (titleElement) {
        const formattedTitle = title.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        titleElement.textContent = formattedTitle;
    }
}

async function fetchAndRenderArtistsByGenre() {

    const searchGenre = currentGenre === '' ? 'pop' : currentGenre;
    const requestUrl = `${API_GENRE_URL}?genre=${encodeURIComponent(searchGenre)}&limit=${limit}&offset=${currentOffset}`;
    
    if (container) container.innerHTML = '<p class="loading-message">Carregando artistas...</p>';
    if (prevButton) prevButton.disabled = true;
    if (nextButton) nextButton.disabled = true;
    updateContentTitle(searchGenre);

    try {
        const response = await fetch(requestUrl);
        if (!response.ok) throw new Error(`Erro de rede: ${response.status}`);
        
        const data = await response.json();
        const results = data.items;
        let htmlContent = '';

        if (results && results.length > 0) {
            results.forEach(artist => {
                htmlContent += renderArtistCard(artist);
            });
        } else {
            htmlContent = `<p class="no-results">Nenhum artista encontrado para o gênero: <strong>${searchGenre.toUpperCase()}</strong>.</p>`;
        }
        if (container) container.innerHTML = htmlContent;

        if (pageInfo) {
            const totalPages = Math.ceil(data.total / data.limit);
            const currentPage = (data.offset / data.limit) + 1;
            pageInfo.textContent = `Página ${currentPage} de ${totalPages}`;
        }
        
        if (prevButton) prevButton.disabled = !data.has_previous; 
        if (nextButton) nextButton.disabled = !data.has_next;
        
    } catch (error) {
        console.error("Erro ao buscar dados do gênero:", error);
        if (container) container.innerHTML = '<p class="error-message">Erro ao carregar os dados. Tente novamente.</p>';
    }
}

async function fetchAndRenderArtistsBySearchTerm(searchTerm) {
    if (!searchTerm || searchTerm.trim() === '') {
        if (container) container.innerHTML = '<p class="no-results">Digite algo para pesquisar artistas.</p>';
        if (prevButton) prevButton.disabled = true;
        if (nextButton) nextButton.disabled = true;
        updateContentTitle("Pesquisa");
        return;
    }

    const requestUrl = `${API_ARTIST_SEARCH_URL}?name=${encodeURIComponent(searchTerm)}`;

    if (container) container.innerHTML = '<p class="loading-message">Buscando artistas...</p>';
    if (prevButton) prevButton.disabled = true;
    if (nextButton) nextButton.disabled = true;
    updateContentTitle(`Resultados para "${searchTerm}"`);

    try {
        const response = await fetch(requestUrl);
        if (!response.ok) throw new Error(`Erro de rede: ${response.status}`);
        
        const data = await response.json();
        const results = data.items;
        let htmlContent = '';

        if (results && results.length > 0) {
            results.forEach(artist => {
                htmlContent += renderArtistCard(artist);
            });
        } else {
            htmlContent = `<p class="no-results">Nenhum artista encontrado para "${searchTerm}".</p>`;
        }
        if (container) container.innerHTML = htmlContent;

        if (pageInfo) pageInfo.textContent = `Resultados para "${searchTerm}"`;
        if (prevButton) prevButton.disabled = true;
        if (nextButton) nextButton.disabled = true;
        
    } catch (error) {
        console.error("Erro ao buscar dados de pesquisa:", error);
        if (container) container.innerHTML = '<p class="error-message">Erro ao buscar resultados. Tente novamente.</p>';
    }
}

function updateBrowserUrl(type, value) {
    const baseUrl = window.location.pathname; 
    let newUrl = baseUrl;
    const params = new URLSearchParams();

    if (type === 'genre') {
        params.append('genre', value);
    } else if (type === 'search') {
        params.append('query', value);
    }
    if (currentOffset > 0) {
        params.append('offset', currentOffset);
    }

    if (params.toString()) {
        newUrl += `?${params.toString()}`;
    }
    window.history.pushState({ type: type, value: value, offset: currentOffset }, '', newUrl);
}

function handleGenreClick(event) {
    const clickedElement = event.currentTarget;
    const newGenre = clickedElement.getAttribute('data-genre');
    
    if (!newGenre || newGenre === currentGenre) return; 

    currentGenre = newGenre;
    currentSearchTerm = "";
    searchInput.value = "";
    currentOffset = 0;

    updateBrowserUrl('genre', newGenre); 

    document.querySelectorAll('.tags-sidebar .tag-item').forEach(tagItem => {
        tagItem.classList.remove('active');
    });
    clickedElement.classList.add('active');
    
    fetchAndRenderArtistsByGenre();
}

function handleSearch() {
    const newSearchTerm = searchInput.value.trim();
    if (!newSearchTerm && !currentSearchTerm) return;

    currentSearchTerm = newSearchTerm;
    currentGenre = "";
    currentOffset = 0;

    updateBrowserUrl('search', newSearchTerm); 

    document.querySelectorAll('.tags-sidebar .tag-item').forEach(tagItem => {
        tagItem.classList.remove('active');
    });
    
    fetchAndRenderArtistsBySearchTerm(currentSearchTerm);
}

window.addEventListener('popstate', (event) => {
    const urlParams = new URLSearchParams(window.location.search);
    const genreFromUrl = urlParams.get('genre') || '';
    const queryFromUrl = urlParams.get('query') || '';
    const offsetFromUrl = parseInt(urlParams.get('offset')) || 0;

    currentOffset = offsetFromUrl;

    if (queryFromUrl) {
        currentSearchTerm = queryFromUrl;
        currentGenre = "";
        searchInput.value = currentSearchTerm;
        document.querySelectorAll('.tags-sidebar .tag-item').forEach(tagItem => tagItem.classList.remove('active'));
        fetchAndRenderArtistsBySearchTerm(currentSearchTerm);
    } else if (genreFromUrl) {
        currentGenre = genreFromUrl;
        currentSearchTerm = "";
        searchInput.value = "";
        document.querySelectorAll('.tags-sidebar .tag-item').forEach(tagItem => {
            tagItem.classList.remove('active');
            if (tagItem.getAttribute('data-genre') === currentGenre) {
                tagItem.classList.add('active');
            }
        });
        fetchAndRenderArtistsByGenre();
    } else {
        currentGenre = 'pop';
        currentSearchTerm = '';
        searchInput.value = '';
        document.querySelectorAll('.tags-sidebar .tag-item').forEach(tagItem => {
            tagItem.classList.remove('active');
            if (tagItem.getAttribute('data-genre') === 'pop') {
                tagItem.classList.add('active');
            }
        });
        fetchAndRenderArtistsByGenre();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    container = document.getElementById('artists-container');
    prevButton = document.getElementById('prev-button');
    nextButton = document.getElementById('next-button');
    pageInfo = document.getElementById('page-info');
    tagsContainer = document.getElementById('tags-container'); 
    searchInput = document.getElementById('searchInput');
    searchButton = document.getElementById('searchBtn');

    if (!container) {
        console.error("Elemento '#artists-container' não encontrado. O script não pode ser inicializado.");
        return;
    }

    if (nextButton) nextButton.addEventListener('click', () => { 
        currentOffset += limit; 
        if (currentSearchTerm) {
            updateBrowserUrl('search', currentSearchTerm);
            fetchAndRenderArtistsBySearchTerm(currentSearchTerm);
        } else {
            updateBrowserUrl('genre', currentGenre);
            fetchAndRenderArtistsByGenre();
        }
    });
    if (prevButton) prevButton.addEventListener('click', () => { 
        currentOffset = Math.max(0, currentOffset - limit); 
        if (currentSearchTerm) {
            updateBrowserUrl('search', currentSearchTerm);
            fetchAndRenderArtistsBySearchTerm(currentSearchTerm);
        } else {
            updateBrowserUrl('genre', currentGenre);
            fetchAndRenderArtistsByGenre();
        }
    });

    const tagElements = tagsContainer ? tagsContainer.querySelectorAll('.tag-item') : [];
    tagElements.forEach(tagItem => {
        tagItem.addEventListener('click', handleGenreClick);
        if (tagItem.getAttribute('data-genre') === currentGenre) {
            tagItem.classList.add('active');
        }
    });
    
    if (searchButton) {
        searchButton.addEventListener('click', handleSearch);
    }
    if (searchInput) {
        searchInput.addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
                handleSearch();
            }
        });
    }
    const urlParams = new URLSearchParams(window.location.search);
    const initialGenre = urlParams.get('genre');
    const initialQuery = urlParams.get('query');
    const initialOffset = parseInt(urlParams.get('offset')) || 0;

    currentOffset = initialOffset;
    if (initialQuery) {
        currentSearchTerm = initialQuery;
        searchInput.value = initialQuery;
        fetchAndRenderArtistsBySearchTerm(currentSearchTerm);
    } else if (initialGenre) {
        currentGenre = initialGenre;
        document.querySelectorAll('.tags-sidebar .tag-item').forEach(tagItem => {
            if (tagItem.getAttribute('data-genre') === currentGenre) {
                tagItem.classList.add('active');
            }
        });
        fetchAndRenderArtistsByGenre();
    } else {
        currentGenre = 'pop';
        document.querySelector('.tags-sidebar .tag-item[data-genre="pop"]').classList.add('active');
        fetchAndRenderArtistsByGenre();
    }
});