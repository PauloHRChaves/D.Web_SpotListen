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
    
    <!--Inserir e estilizar cabeçalho na página-->
    <script src="../static/js/header.js"></script>
    <link rel="stylesheet" href="../static/css/header.css">

    <!--Bootstrap Icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-dark: #1e1e1e;
            --bg-card: #2c2c2c;
            --text-light: #ffffff;
            --text-subtle: #aaaaaa;
            --accent-color: #ff00ff; /* Cor fictícia, ajuste se quiser */
            --border-radius: 12px;
            --spacing: 20px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #000;
            display: flex;
            justify-content: center;
        }
        main{
            margin-top: 80px;
            flex-grow: 1;
            overflow-y: auto;
        }
        main::-webkit-scrollbar {
            width: clamp(0.1rem, 0.7vw, 0.5rem);
        }
        main::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #c0c0c0 20%, white 50%);
            border-radius: var(--radius);
        }

        .profile-container {
            background-color: var(--bg-dark);
            color: var(--text-light);
        }

        .profile-header {
            position: relative;
            padding: 3rem 4rem;
        }

        .header-content {
            text-align: center;
            display: flex;
            align-content: center;
            gap: 1rem;
        }

        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid var(--bg-dark);
            margin-bottom: 10px;
        }

        .username {
            font-size: 2.5em;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
            align-self: center;
        }

        .profile-main {
            display: flex;
            gap: var(--spacing);
            padding: 0 var(--spacing) var(--spacing);
        }

        .left-section {
            flex: 2;
            min-width: 60%;
        }

        .right-section {
            flex: 1;
            min-width: 30%;
            display: flex;
            flex-direction: column;
            gap: var(--spacing);
        }

        .section-card {
            background-color: var(--bg-card);
            padding: var(--spacing);
            border-radius: var(--border-radius);
            margin-bottom: var(--spacing);
        }

        .section-card h2 {
            font-size: 1.2em;
            margin-bottom: 15px;
        }

        .track-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .track-item {
            text-align: center;
        }

        .track-item img {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 8px;
            object-fit: cover;
        }

        .view-more {
            background-color: var(--text-subtle);
            color: var(--bg-dark);
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            display: block;
            width: 100%;
            opacity: 0.7;
        }

        .artist-item, .artist-item-side, .playlist-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .artist-item img, .artist-item-side img, .playlist-item img {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            margin-right: 15px;
            object-fit: cover;
        }

        .genre-chart-container {
            padding: 10px 0;
        }

        .genre-chart {
            display: flex;
            align-items: flex-end;
            height: 100px;
            gap: 10px;
        }

        .chart-bar {
            width: 15px;
            background-color: var(--accent-color);
            border-radius: 3px 3px 0 0;
        }

        .bar-1 { height: 70%; background-color: #ff5733; }
        .bar-2 { height: 90%; background-color: #33ff57; }
        .bar-3 { height: 50%; background-color: #3357ff; }
        .bar-4 { height: 80%; background-color: #ff33a1; }

        .favorite-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            /* padding: 1rem 1.5rem; */
            border-radius: 1rem;
            /* border: 2px solid #04455f5a; */
            /* background-color: rgba(25, 73, 101, 0.34); */
            margin-bottom: var(--spacing);
            background-color: var(--bg-card);
            padding: var(--spacing);

            .arrow-short {
                font-size: 2rem;
                cursor: pointer;
                flex-shrink: 0;
                user-select: none;
            }

            .songcontent {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 1rem;
            
            
                .scroller-container {
                    width: calc(6 * 150px + 5 * 1rem);
                    overflow-x: scroll;
                    overflow-y: hidden;
                }
                
                .scroller-container::-webkit-scrollbar {
                    display: none;
                }
                
                .scroller-wrapper {
                    display: flex;
                    gap: 1rem;
                    flex-wrap: nowrap;
                }
                
                .box {
                    display: flex;
                    flex-direction: column;
                    width: 150px;
                    height: fit-content;
                    flex-shrink: 0;
                    
                    .songsliked {
                        height: 150px;
                        border-radius: var(--radius);
                        background-color: rgba(109, 146, 168, 0.34);
                    }

                    .songname {
                        font-size: clamp(15px, 1vh, 20px);
                        word-wrap: break-word;
                        margin-top: 5px;
                    }
                }
            }
        }
    </style>
    <style>
        body {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s;
        }
        body.is-authenticated {
            opacity: 1;
            visibility: visible;
        }
        #vincular-spotify{
                background: None;
                border: none;
            .bi-link-45deg{
                font-size: 4rem;
                color: white;
                cursor: pointer;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <header id="header-placeholder"><?php include 'header.php'; ?></header>

    <main class="profile-container">
        <div class="profile-header">
            <div class="header-content">
                <img class="profile-picture" id="profile-pic" src="../static/imgs/profile-icon.png" alt="Foto de Perfil">
                <h1 class="username" id="username-display"></h1>
                <button id="vincular-spotify">
                    <i class="bi bi-link-45deg"></i>
                </button>
            </div>
        </div>

        <div class="profile-main">
            <div class="left-section">
                <section class="favorite-section">
                    <h2>Ouvidas Recentes</h2>
                    <div class="songcontent">
                        <i class="bi bi-arrow-left-short arrow-short" onclick="scrollBooks(-1)"></i>
                        <div class="scroller-container">
                            <div class="scroller-wrapper" id="recent-tracks-container">
                                </div>
                        </div>
                        <i class="bi bi-arrow-right-short arrow-short" onclick="scrollBooks(1)"></i>
                    </div>
                </section>

                <section class="favorite-artists-list section-card collapsible">
                    <h2>Artistas Favoritos</h2>
                    <div class="artist-item">
                        <img src="../static/imgs/profile-icon.png" alt="Foto do Artista">
                        <div class="artist-info">
                            <h3>Florence + The Machine</h3>
                            <p>Descrição/Status</p>
                        </div>
                    </div>
                </section>

                <section class="saved-albums section-card collapsible">
                    <h2>Álbuns Salvos</h2>
                </section>
            </div>

            <div class="right-section">
                <section class="favorite-artists-side section-card">
                    <h2>Artistas Favoritos</h2>
                    <div class="artist-item-side">
                        <img src="../static/imgs/profile-icon.png" alt="Capa">
                        <div class="artist-info-side">
                            <h3>Artista em Destaque</h3>
                            <p>Detalhe</p>
                        </div>
                    </div>
                    </section>

                <section class="created-playlists section-card collapsible">
                    <h2>Playlists Criadas</h2>
                    <div id="playlists-container" class="playlists-grid">
                    </div>
                </section>

                <section class="top-genres section-card collapsible">
                    <h2>Top Gêneros</h2>
                    <div class="genre-chart-container">
                        <div class="genre-chart">
                            <div class="chart-bar bar-1"></div>
                            <div class="chart-bar bar-2"></div>
                            <div class="chart-bar bar-3"></div>
                            <div class="chart-bar bar-4"></div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>


    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const manualSessionId = localStorage.getItem('manualSessionId'); 
            const profilePicElement = document.getElementById('profile-pic');
            const usernameDisplayElement = document.getElementById('username-display');
            const defaultProfileImg = '../static/imgs/profile-icon.png';
            
            const redirectToLogin = () => {
                localStorage.removeItem('manualSessionId');
                window.location.href = 'http://127.0.0.1:8132/templates/auth/login.html'; 
            };

            if (!manualSessionId) {
                redirectToLogin();
                return;
            }

            const url = `http://127.0.0.1:8131/logged-in?PHPSESSID=${manualSessionId}`;

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });
                
                const result = await response.json();
                const userData = result.user; 

                if (response.ok && userData && result.isLoggedIn) {
                    
                    let displayName = userData.username;
                    let profileImgSrc = defaultProfileImg;
                    
                    const spotifyData = userData.spotify_info;
                    
                    if (spotifyData && spotifyData.SPFY_USERNAME) {
                        displayName = spotifyData.SPFY_USERNAME;
                        
                        if (spotifyData.PROFILE_IMG) {
                            profileImgSrc = spotifyData.PROFILE_IMG;
                        }
                    }

                    usernameDisplayElement.textContent = displayName; 
                    profilePicElement.src = profileImgSrc;
                    
                    document.body.classList.add('is-authenticated');

                } else {
                    redirectToLogin();
                }
            } catch (error) {
                console.error('Erro de requisição:', error);
                redirectToLogin();
            }
        });
    
    </script>
    <!--Scroll para favorite-section-->


    <script>
        document.getElementById('vincular-spotify').addEventListener('click', () => {
            const manualSessionId = localStorage.getItem('manualSessionId');
            
            if (manualSessionId) {
                window.location.href = `http://127.0.0.1:8131/spotify/auth?PHPSESSID=${manualSessionId}`;
            } else {
                window.location.href = 'http://127.0.0.1:8132/templates/auth/login.html'; 
            }
        });
    </script>

    <script>
        const manualSessionId = localStorage.getItem('manualSessionId'); 
        
        const PLAYLISTS_URL = 'http://127.0.0.1:8131/spotify/my/playlists';
        const RECENT_TRACKS_URL_BASE = 'http://127.0.0.1:8131/spotify/my/recent-tracks';

        function scrollBooks(direction) {
            const scroller = document.querySelector('.scroller-container');
            const scrollAmount = 166;
            scroller.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth'
            });
        }

        async function loadMyPlaylists() {
            const container = document.getElementById('playlists-container');
            container.innerHTML = ''; 

            if (!manualSessionId) {
                container.innerHTML = '<p>Erro: Sessão de usuário não encontrada. Por favor, faça login.</p>';
                return;
            }

            try {
                const response = await fetch(`${PLAYLISTS_URL}?PHPSESSID=${manualSessionId}`, {
                    method: 'GET',
                    credentials: 'include'
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({})); 
                    console.error('Erro ao buscar playlists:', errorData);
                    container.innerHTML = '<p>Não foi possível carregar as playlists.</p>';
                    return;
                }

                const data = await response.json();
                const playlists = data.items || [];

                if (playlists.length === 0) {
                    container.innerHTML = '<p>Nenhuma playlist encontrada.</p>';
                    return;
                }

                playlists.forEach(pl => {
                    const div = document.createElement('div');
                    div.className = 'playlist-item';
                    div.id = 'my-playlist';

                    const imageUrl = pl.images && pl.images.length > 0 
                        ? pl.images[0].url 
                        : '../static/imgs/profile-icon.png';

                    div.innerHTML = `
                        <img src="${imageUrl}" alt="Capa da Playlist">
                        <div class="playlist-info">
                            <h3>${pl.name}</h3>
                            <p>${pl.tracks.total} faixas</p>
                        </div>
                    `;
                    container.appendChild(div);
                });

            } catch (error) {
                console.error('Erro de conexão:', error);
                container.innerHTML = '<p>Ocorreu um erro de conexão.</p>';
            }
        }

        async function loadRecentTracks() {
            const container = document.getElementById('recent-tracks-container');
            container.innerHTML = '';

            if (!manualSessionId) {
                container.innerHTML = '<p>Erro: Sessão de usuário não encontrada. Por favor, faça login.</p>';
                return;
            }

            try {
                const fullUrl = `${RECENT_TRACKS_URL_BASE}?PHPSESSID=${manualSessionId}`;

                const response = await fetch(fullUrl, {
                    method: 'GET',
                    credentials: 'include'
                });
                
                if (!response.ok) {
                    const errorData = await response.text();
                    console.error('Erro ao buscar faixas:', errorData);
                    container.innerHTML = '<p>Erro: não foi possível carregar as músicas. Vincule sua conta Spotify.</p>';
                    return;
                }

                const data = await response.json();
                const tracks = data.items || [];

                if (tracks.length === 0) {
                    container.innerHTML = '<p>Nenhuma música recente encontrada.</p>';
                    return;
                }

                tracks.forEach(item => {
                    const track = item.track;
                    const artists = track.artists.map(a => a.name).join(', ');
                    const imageUrl = track.album.images[0].url;

                    const box = document.createElement('div');
                    box.className = 'box';
                    box.innerHTML = `
                        <div class="songsliked">
                            <img src="${imageUrl}" alt="${track.name}" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <h3 class="songname">${track.name}</h3>
                        <p class="songowner">${artists}</p>
                    `;
                    container.appendChild(box);
                });

            } catch (error) {
                console.error('Erro de conexão:', error);
                container.innerHTML = '<p>Erro de conexão com a API.</p>';
            }
        }

        window.addEventListener('load', loadMyPlaylists);
        window.addEventListener('load', loadRecentTracks);

    </script>
</body>
</html>