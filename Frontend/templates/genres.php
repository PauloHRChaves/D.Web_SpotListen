<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!--Configurações de exibição (caracteres especiais/design responsivo)-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!--Nome da Aba-->
    <title>D.Web-PHP</title>

    <!--Font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <!--Favicon-->

    <!--Toastfy-->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <!--Inserir e estilizar cabeçalho na página-->
    <script src="../static/js/header.js"></script>
    <link rel="stylesheet" href="../static/css/header.css">

    <!--Bootstrap Icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

    <!--JS da página-->

    <!--CSS da página-->
    <link rel="stylesheet" href="../static/css/genres.css">

    <style>
        .toastify{
            box-shadow: none;
            font-size: clamp(0.5rem, 1vw, 1.2rem);
            border-radius: var(--radius);
            font-family: "Work Sans", sans-serif;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!--Cabeçalho de navegação-->
    <header id="header-placeholder"><?php include 'header.php'; ?></header>

    <main>
        <section class="first-container" id="home">
            <!--Barra de busca (AINDA N IMPLEMENTADO)-->
            <div class="search-container">
                <div class="search-bar">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput">
                    <button class="search-btn" id="searchBtn" style="background: none; border: none; font-size: 2rem;">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </section>

        <section class="second-container" id="home">
            <div class="tags">
                <li><span data-genre="pop" class="tag active">Pop</span></li>
                <li><span data-genre="rock" class="tag">Rock</span></li>
                <li><span data-genre="hip hop" class="tag">HipHop</span></li>
                <li><span data-genre="electronic" class="tag">Eletronica</span></li>
                <li><span data-genre="reggae" class="tag">Reggae</span></li>
                <li><span data-genre="brazil mpb" class="tag">MPB</span></li>
                <li><span data-genre="classic" class="tag">Clássica</span></li>
                <li><span data-genre="indie" class="tag">Indie</span></li>
            </div>

            <div class="artists-grid" id="artists-container">
            </div>
            
            <div class="pagination-controls">
                <button id="prev-button" disabled>&#x2190; Anterior</button>
                <span id="page-info">Página 1</span>
                <button id="next-button" disabled>Próxima &#x2192;</button>
            </div>
        </section>
    </main>

    <script>
        function showToast() {
            Toastify({
                text: 'Você precisa estar logado.',
                duration: 4000,
                gravity: "top",
                position: "right",
                backgroundColor: "#303F55",
                boxShadow:"none",
            }).showToast();
        }
    </script>
    
    <script>
        let currentGenre = "<?php echo htmlspecialchars($_GET['genre'] ?? ''); ?>"; 
        let currentOffset = 0;
        const limit = 12;
        
        const API_URL = `http://localhost:8131/spotify/search/genre`;
        
        let container, prevButton, nextButton, pageInfo, tagsContainer;

        async function fetchAndRenderArtists() {
            const searchGenre = currentGenre === '' ? 'pop' : currentGenre;

            const requestUrl = `${API_URL}?genre=${encodeURIComponent(searchGenre)}&limit=${limit}&offset=${currentOffset}`;
            
            if (container) container.innerHTML = '<p>Carregando artistas...</p>';
            if (prevButton) prevButton.disabled = true;
            if (nextButton) nextButton.disabled = true;

            try {
                const response = await fetch(requestUrl);
                if (!response.ok) throw new Error(`Erro de rede: ${response.status}`);
                
                const data = await response.json();
                const results = data.items;
                let htmlContent = '';

                if (results && results.length > 0) {
                    results.forEach(artist => {
                        const DEFAULT_IMAGE = '/static/imgs/profile-icon.png';
                        htmlContent += `
                            <div class="artist-card">
                                <img src="${artist.image_url || DEFAULT_IMAGE}" alt="${artist.name}">
                                <h2>${artist.name}</h2>
                                <p>Popularidade: ${artist.popularity}</p>
                            </div>
                        `;
                    });
                } else {
                    htmlContent = `<p>Nenhum artista encontrado para o gênero: <strong>${searchGenre.toUpperCase()}</strong>.</p>`;
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
                console.error("Erro ao buscar dados:", error);
                if (container) container.innerHTML = '<p>Erro ao carregar os dados. Tente novamente.</p>';
            }
        }

        function updateBrowserUrl(newGenre) {
            const baseUrl = window.location.pathname; 
            const newUrl = `${baseUrl}?genre=${encodeURIComponent(newGenre)}`;
            window.history.pushState({ genre: newGenre }, '', newUrl);
        }
        
        function handleGenreClick(event) {
            const clickedElement = event.currentTarget;
            const newGenre = clickedElement.getAttribute('data-genre');
            
            if (!newGenre || newGenre === currentGenre) return; 

            currentGenre = newGenre;
            currentOffset = 0;

            updateBrowserUrl(newGenre); 

            document.querySelectorAll('.tags .tag').forEach(tag => {
                tag.classList.remove('active');
            });
            clickedElement.classList.add('active');

            fetchAndRenderArtists();
        }

        window.addEventListener('popstate', (event) => {
            const urlParams = new URLSearchParams(window.location.search);
            const genreFromUrl = urlParams.get('genre') || 'pop'; 

            if (genreFromUrl === currentGenre) return; 

            currentGenre = genreFromUrl;
            currentOffset = 0; 

            document.querySelectorAll('.tags .tag').forEach(tag => {
                tag.classList.remove('active');
                if (tag.getAttribute('data-genre') === currentGenre) {
                    tag.classList.add('active');
                }
            });
            
            // 3. Busca o conteúdo
            fetchAndRenderArtists();
        });

        document.addEventListener('DOMContentLoaded', () => {
            container = document.getElementById('artists-container');
            prevButton = document.getElementById('prev-button');
            nextButton = document.getElementById('next-button');
            pageInfo = document.getElementById('page-info');
            tagsContainer = document.querySelector('.tags');

            if (!container) {
                console.error("Elemento '#artists-container' não encontrado. O script não pode ser inicializado.");
                return;
            }

            if (nextButton) {
                nextButton.addEventListener('click', () => {
                    currentOffset += limit; 
                    fetchAndRenderArtists();
                });
            }

            if (prevButton) {
                prevButton.addEventListener('click', () => {
                    currentOffset = Math.max(0, currentOffset - limit); 
                    fetchAndRenderArtists();
                });
            }

            const tagElements = tagsContainer ? tagsContainer.querySelectorAll('.tag') : [];
            tagElements.forEach(tag => {
                tag.addEventListener('click', handleGenreClick);
            });
            
            document.querySelectorAll('.tags .tag').forEach(tag => {
                tag.classList.remove('active');
                if (tag.getAttribute('data-genre') === currentGenre) {
                    tag.classList.add('active');
                }
            });

            fetchAndRenderArtists();
        });
    </script>

</body>
</html>