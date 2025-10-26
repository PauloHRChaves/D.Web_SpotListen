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
    <link rel="apple-touch-icon" sizes="180x180" href="/static/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/static/favicon_io//favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/static/favicon_io//favicon-16x16.png">
    <link rel="manifest" href="/static/favicon_io//site.webmanifest">

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
    <link rel="stylesheet" href="../static/css/artist-info.css">

    <style>
        .toastify{
            box-shadow: none;
            font-size: clamp(0.5rem, 1vw, 1.2rem);
            border-radius: var(--radius);
            font-family: "Work Sans", sans-serif;
            font-weight: 600;
        }
        main{
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <!--Cabeçalho de navegação-->
    <header id="header-placeholder"><?php include 'header.php'; ?></header>

    <main>
        <div id="artist-details-container">
            <h1 id="artist-name-title"></h1> 
            <p id="loading-message">Buscando informações detalhadas...</p>
            <div id="biography-content"></div> 
        </div>
    </main>

    <script>
        function getArtistNameFromUrl() {
            const params = new URLSearchParams(window.location.search);
            
            const artistName = params.get('artist');
            
            const titleElement = document.getElementById('artist-name-title');
            
            titleElement.textContent = artistName;
            
        }

        document.addEventListener('DOMContentLoaded', getArtistNameFromUrl);
    </script>
</body>
</html>