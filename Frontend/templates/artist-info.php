<?php
$artistName = htmlspecialchars($_GET['artist'] ?? 'Artista Desconhecido');
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
            <h1><?php echo $artistName; ?></h1>
            <p id="loading-message">Buscando informações detalhadas...</p>
            
            <div id="biography-content"></div> 
        </div>
    </main>

    <script>
        const currentArtist = "<?php echo $artistName; ?>"; 
        const API_URL = 'http://localhost:8131/wiki/artist-info';
        
        const loadingMessage = document.getElementById('loading-message');
        const biographyContent = document.getElementById('biography-content');
        const artistTitle = document.querySelector('#artist-details-container h1');
        
        async function fetchBiography() {
            try {
                const endpoint = `${API_URL}?artistName=${encodeURIComponent(currentArtist)}`;
                
                const response = await fetch(endpoint);
                
                if (!response.ok) {
                    throw new Error(`Erro HTTP! Status: ${response.status}`);
                }

                const data = await response.json();

                if (data.error) {
                    biographyContent.innerHTML = `<p class="error">Erro: ${data.error}</p>`;
                    return;
                }
                
                artistTitle.textContent = data.title; 

                biographyContent.innerHTML = data.biography_html;

            } catch (error) {
                console.error("Erro ao buscar biografia:", error);
                biographyContent.innerHTML = `<p class="error">Falha ao carregar informações. Tente novamente mais tarde.</p>`;
            } finally {
                if (loadingMessage) {
                    loadingMessage.remove();
                }
            }
        }

        if (currentArtist && currentArtist !== 'Artista Desconhecido') {
            fetchBiography();
        } else {
            loadingMessage.textContent = 'Por favor, selecione um artista válido.';
        }
    </script>

</body>
</html>