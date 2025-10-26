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
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700;800&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <!--Favicon-->
    <link rel="apple-touch-icon" sizes="180x180" href="/static/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/static/favicon_io//favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/static/favicon_io//favicon-16x16.png">
    <link rel="manifest" href="/static/favicon_io//site.webmanifest">
    
    <!--Toastfy-->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <!--Inserir e estilizar cabeçalho na página-->
    <script src="/static/js/header.js"></script>
    <link rel="stylesheet" href="/static/css/header.css">

    <!--Bootstrap Icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

    <!--JS da página-->
    <script src="/static/js/categories.js"></script>

    <!--CSS da página-->
    <link rel="stylesheet" href="/static/css/genres.css">

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
    
    <header id="header-placeholder"><?php include 'header.php'; ?></header>

    <main>
        <div class="banner-search-container">
            <div class="search-container">
                <div class="search-bar">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Pesquisar Artistas, Álbuns ou Músicas...">
                    <button class="search-btn" id="searchBtn">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="main-content-wrapper">
            <aside class="sidebar-genres">
                <h3 class="sidebar-title">Gêneros Principais</h3>
                <ul class="tags-sidebar" id="tags-container">
                    <li data-genre="pop" class="tag-item"><span class="tag">Pop</span></li>
                    <li data-genre="rock" class="tag-item"><span class="tag">Rock</span></li>
                    <li data-genre="hip hop" class="tag-item"><span class="tag">HipHop</span></li>
                    <li data-genre="electronic" class="tag-item"><span class="tag">Eletrônica</span></li>
                    <li data-genre="reggae" class="tag-item"><span class="tag">Reggae</span></li>
                    <li data-genre="brazil mpb" class="tag-item"><span class="tag">MPB</span></li>
                    <li data-genre="classic" class="tag-item"><span class="tag">Clássica</span></li>
                    <li data-genre="indie" class="tag-item"><span class="tag">Indie</span></li>
                </ul>
            </aside>

            <section class="main-content">       
                <div class="content-header">
                    <h1 id="current-genre-title">Pop</h1>
                    
                    <div class="subgenre-tags">
                        <span class="subgenre-tag" style="background-color: #e53935;">Metal</span>
                        <span class="subgenre-tag" style="background-color: #d81b60;">Punk</span>
                        <span class="subgenre-tag" style="background-color: #6a1b9a;">Grunge</span>
                        <span class="subgenre-tag" style="background-color: #ff6f00;">Hard Rock</span>
                    </div>
                </div>

                <div class="artists-grid" id="artists-container"></div>

                <div class="pagination-controls">
                    <button id="prev-button" disabled>&#x2190; Anterior</button>
                    <span id="page-info">Página 1</span>
                    <button id="next-button" disabled>Próxima &#x2192;</button>
                </div>
            </section>
        </div>
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

</body>
</html>