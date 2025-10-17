<?php
$genre = htmlspecialchars($_GET['genre'] ?? '');
$initialOffset = 0;
$limit = 16;
?>
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
        .second-container{
            position: relative;

            .pop{
                width: 100%;
                height: 200px;
                background-color: #6f6f6fff;
            }
        }
        .artists-grid {
            color: white;
            display: grid; 
            
            grid-template-columns: repeat(4, 1fr); 
            
            gap: 1.5rem;

            .artist-card {
                width: 100%; 
                text-align: center;
            }

            img {
                width: 100%; 
                height: auto;
                aspect-ratio: 1/1;
                object-fit: cover;
                border-radius: 0.5rem;
            }
        }

    </style>
    <style>
        
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

        <article class="secont-container" id="home">
            <div class="tags">
                <li><a href="/templates/genres.php?genre=Pop" class="tag active">Pop</a></li>
                <li><a href="/templates/genres.php?genre=Rock" class="tag">Rock</a></li>
                <li><a href="/templates/genres.php?genre=Hip%20Hop" class="tag">HipHop</a></li>
                <li><a href="/templates/genres.php?genre=Electronic" class="tag">Eletronica</a></li>
                <li><a href="/templates/genres.php?genre=Reggae" class="tag">Reggae</a></li>
                <li><a href="/templates/genres.php?genre=Brazil%20mpb" class="tag">MPB</a></li>
                <li><a href="/templates/genres.php?genre=Classic"  class="tag">Clássica</a></li>
                <li><a href="/templates/genres.php?genre=Indie" id="auth-tag" class="tag">Indie</a></li>
            </div>

            <div id="artists-container" class="artists-grid">
            </div>
            
            <div class="pagination-controls">
                <button id="prev-button" disabled>&#x2190; Anterior</button>
                <span id="page-info">Página 1</span>
                <button id="next-button" disabled>Próxima &#x2192;</button>
            </div>
        </article>
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
        // Variáveis de estado
        let currentGenre = "<?= $genre ?>"; 
        let currentOffset = <?= $initialOffset ?>;
        const limit = <?= $limit ?>;
        
        const API_URL = `http://localhost:8131/spotify/search/genre`;
        

        async function fetchAndRenderArtists() {
            const searchGenre = currentGenre === '' ? 'Pop' : currentGenre;

            const requestUrl = `${API_URL}?genre=${encodeURIComponent(searchGenre)}&limit=${limit}&offset=${currentOffset}`;
            
            // ... (variáveis de container e botões) ...
            const container = document.getElementById('artists-container');
            const prevButton = document.getElementById('prev-button');
            const nextButton = document.getElementById('next-button');
            const pageInfo = document.getElementById('page-info');

            container.innerHTML = '<p>Carregando artistas...</p>';
            prevButton.disabled = true;
            nextButton.disabled = true;

            try {
                const response = await fetch(requestUrl);
                const data = await response.json();
                
                const results = data.items;

                // ... (lógica de renderização dos cards de artista) ...
                if (results && results.length > 0) {
                    container.innerHTML = ''; 
                    results.forEach(artist => {
                        const card = document.createElement('div');
                        const DEFAULT_IMAGE = '/static/imgs/profile-icon.png';
                        card.className = 'artist-card';
                        card.innerHTML = `
                            <img src="${artist.image_url || DEFAULT_IMAGE}" alt="${artist.name}">
                            <h2>${artist.name}</h2>
                            <p>Popularidade: ${artist.popularity}</p>
                        `;
                        container.appendChild(card);
                    });
                }else {
                    let message = `Nenhum artista encontrado para o gênero: <strong>${currentGenre}</strong>.`;

                    if (currentGenre === '') {
                        // Se a busca foi por "Pop" mas currentGenre é vazio, 
                        // a mensagem deve refletir que é uma lista geral, não um erro.
                        // Se você fez a busca com sucesso, mas o resultado é 0, a mensagem ainda é de erro:
                        message = 'Nenhum artista popular encontrado na lista principal. Tente outro gênero.'; 
                    }
                    container.innerHTML = `<p>${message}</p>`;
                }

                // Usa os metadados retornados pelo Service
                const totalPages = Math.ceil(data.total / data.limit);
                const currentPage = (data.offset / data.limit) + 1;

                pageInfo.textContent = `Página ${currentPage} de ${totalPages}`;
                
                // Usa as flags de navegação retornadas pelo Backend
                prevButton.disabled = !data.has_previous; 
                nextButton.disabled = !data.has_next;
                
            } catch (error) {
                console.error("Erro ao buscar dados:", error);
                container.innerHTML = '<p>Erro ao carregar os dados. Tente novamente.</p>';
            }
        }
        
        // Manipuladores de eventos de Paginação (INALTERADOS e CORRETOS)
        document.getElementById('next-button').addEventListener('click', () => {
            currentOffset += limit; 
            fetchAndRenderArtists();
        });

        document.getElementById('prev-button').addEventListener('click', () => {
            currentOffset = Math.max(0, currentOffset - limit); 
            fetchAndRenderArtists();
        });

        // Inicia o carregamento
        document.addEventListener('DOMContentLoaded', fetchAndRenderArtists);

    </script>

</body>
</html>