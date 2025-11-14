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

    <style>
        :root {
            --bg-dark: #1e1e1e;
            --bg-card: #2c2c2c;
            --text-light: #ffffff;
            --text-subtle: #aaaaaa;
            --border-radius: 12px;
            --spacing: 20px;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #000;
            display: flex;
            justify-content: center;
        }
        main{
            background: linear-gradient(to bottom, #000312, #000c02);
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
            align-items: center;
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

        .artist-item-side img, .playlist-item img {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            margin-right: 15px;
            object-fit: cover;
        }

        .recent-tracks-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: var(--spacing);
            padding: var(--spacing);
        }

        .arrow-short {
            font-size: 2rem;
                cursor: pointer;
                flex-shrink: 0;
                user-select: none;
                color: #000000;
                background-color: #ffffff;
                display: flex;
                border-radius: 50%;
        }

        .songcontent {
            display: flex;
            align-items: center;
            justify-content: space-between; 
            gap: 1rem;
            overflow: hidden;
        }

        .scroller-container {
            flex-grow: 1; 
            min-width: 0;
            
            overflow-x: scroll;
            overflow-y: hidden;
        }

        .scroller-container::-webkit-scrollbar {
            display: none;
        }

        .scroller-wrapper {
            display: flex;
            gap: 15px;
            flex-wrap: nowrap;
            min-width: 0;
        }

        .box {
            display: flex;
            flex-direction: column;
            width: 195px;
            height: fit-content;
            flex-shrink: 0;
        }

        .box .songsliked {
            height: 195px;
        }

        .box .songsliked img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .box .songname, 
        .box .artist-name {
            font-size: clamp(1rem, 1vh, 2rem); 
            margin-top: 5px;
            
            white-space: nowrap; 
            overflow: hidden;    
            text-overflow: ellipsis;
            width: 100%;
        }

        .background-playing {
            background: url('https://www.nicepng.com/png/full/17-176915_record-png-jj-drummond-records-disco-de-vinil.png');
            width: 150px;
            height: 150px;
            background-size: cover;
            background-position: center;
            display:flex;
            justify-content: center;
            align-items: center;
            
        }
        .playing{
            width: 90px; 
            height: 90px; 
            border-radius: 50%;
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg); 
            }
            to {
                transform: rotate(360deg);
            }
        }

    </style>
    
    <style>
        body.is-authenticated {
            opacity: 1;
            visibility: visible;
        }

        .vinc{
            width: 100%;
            font-size: 1.5rem;
            text-align: center; 
            padding: 1rem; 
            color: #fff;

            h2{}

            p{font-size: 1.3rem;}

            #vincular-spotify{
                font-size: 1.6rem;
                background-color: #1DB954; 
                color: white; 
                border: none;
                padding: 14px 24px;    
                border-radius: 4rem; 
                font-weight: bold; 
                cursor: pointer;
                transition: 0.2s ease-in-out;
            }
            #vincular-spotify:hover{
                background-color: #1d9e4aff;
                transform: scale(1.1);
                transition: transform 0.2s ease-in-out;
            }
        }
    </style>

    <style>
        .toastify{
            box-shadow: none;
            font-size: clamp(0.5rem, 1vw, 1.2rem);
            border-radius: var(--radius);
            font-family: "Work Sans", sans-serif;
            font-weight: 600;
            min-width: 15%;
            justify-items: center;
            margin-top: 80px;
        }
    </style>

    <style>
        #myTopArtists {
            margin-top: 1rem;
            display: block; 
        }

        .bar-row {
            display: flex;
            flex-direction: row;
            align-items: center;
            margin-bottom: 15px;
            padding: 0 10px;
            height: 30px;
        }

        .genre-label {
            width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 500;
            padding-right: 15px;
        }

        .bar-chart-wrapper {
            flex-grow: 1;
            background-color: #333;
            height: 100%;
            border-radius: 4px;
            overflow: hidden;
        }

        .bar {
            height: 100%;
            background-color: #1799d17d;
            transition: width 0.5s ease-out;
        }

        .bar-value {
            width: 30px;
            text-align: right;
            font-weight: bold;
            font-size: 1em;
            color: var(--color-primary);
        }
    </style>

    <style>
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            color: var(--text-light);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            opacity: 1;
            visibility: visible;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #333;
            border-top: 5px solid #1799d1;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loaded #loading-overlay {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
    </style>

</head>
<body>
    <div id="loading-overlay">
        <div class="spinner"></div>
        <h3>Carregando seu perfil musical...</h3>
    </div>

    <!-- Navigation Header -->
    <header id="header-placeholder"><?php include 'header.php'; ?></header>

    <main class="profile-container">
        <div class="profile-header">
            <div class="header-content">
                <img class="profile-picture" id="profile-pic" src="../static/imgs/profile-icon.png" alt="Foto de Perfil">
                <h1 class="username" id="username-display"></h1>

                <div id="current-track-container">
                    <div id="track-info"></div>
                </div>

            </div>
        </div>

        <div class="profile-main" id="main">
            <div class="left-section">
                <section class="recent-tracks-section">
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
                    <h2>Gêneros Mais Ouvidos</h2>
                    <div class="artist-item" id="myTopArtists">
                    </div>
                </section>

                <section class="saved-albums section-card collapsible">
                    <h2>Álbuns Salvos</h2>
                </section>
            </div>

            <div class="right-section">
                <section class="created-playlists section-card collapsible">
                    <h2>Playlists</h2>
                    <div id="playlists-container" class="playlists-grid">
                    </div>
                </section>

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

            </div>
        </div>
    </main>
    
    <script>
        let isSpotifyLinked = false;
        
        const redirectToLogin = () => {
            localStorage.removeItem('manualSessionId');
            window.location.replace('http://127.0.0.1:8132/templates/auth/login.html'); 
        };
        
        async function checkAndLoadEssentialUserData() {
            const manualSessionId = localStorage.getItem('manualSessionId'); 
            const profilePicElement = document.getElementById('profile-pic');
            const usernameDisplayElement = document.getElementById('username-display');
            const defaultProfileImg = '../static/imgs/profile-icon.png';

            if (!manualSessionId) {
                redirectToLogin();
                return false;
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
                        isSpotifyLinked = true;
                        displayName = spotifyData.SPFY_USERNAME;
                        
                        if (spotifyData.PROFILE_IMG) {
                            profileImgSrc = spotifyData.PROFILE_IMG;
                        }
                    }else{
                        isSpotifyLinked = false;
                    }

                    usernameDisplayElement.textContent = displayName; 
                    profilePicElement.src = profileImgSrc;
                    
                    return true; 
                } else {
                    redirectToLogin();
                    return false;
                }
            } catch (error) {
                console.error('Erro de requisição:', error);
                redirectToLogin();
                return false;
            }
        }
    </script>

    <script>
        const manualSessionId = localStorage.getItem('manualSessionId'); 
        
        const PLAYLISTS_URL = 'http://127.0.0.1:8131/spotify/my/playlists';
        const RECENT_TRACKS_URL_BASE = 'http://127.0.0.1:8131/spotify/my/recent-tracks';
        // const CURRENT_TRACK_URL = 'http://127.0.0.1:8131/spotify/my/current-track'
        const MY_TOP_ARTISTS = 'http://127.0.0.1:8131/spotify/my/top-artists';

        function scrollBooks(direction) {
            const scroller = document.querySelector('.scroller-container');
            const scrollAmount = 209;
            scroller.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth'
            });
        }

        function displayFeedback(message, type) {
            Toastify({
                text: message,
                duration: 4000,
                gravity: "top",
                position: "center",
                backgroundColor: "#350101ff",
            }).showToast();
        }

        function handleUrlError() {
            const urlParams = new URLSearchParams(window.location.search);
            const errorMessage = urlParams.get('error');
            
            if (errorMessage) {
                displayFeedback(errorMessage);
                urlParams.delete('error');

                const newSearch = urlParams.toString().length > 0 ? '?' + urlParams.toString() : '';
                window.history.replaceState({}, document.title, window.location.pathname + newSearch);
            }
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
                    container.innerHTML = '<p>Vincule com a sua conta Spotify.</p>';
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

            try {
                const fullUrl = `${RECENT_TRACKS_URL_BASE}?PHPSESSID=${manualSessionId}`;

                const response = await fetch(fullUrl, {
                    method: 'GET',
                    credentials: 'include'
                });
                
                if (!response.ok) {
                    const errorData = await response.text();
                    console.error('Erro ao buscar faixas:', errorData);
                    container.innerHTML = '<p>Vincule com a sua conta Spotify.</p>';
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
                            <img src="${imageUrl}" alt="${track.name}"">
                        </div>
                        <h3 class="songname">${track.name}</h3>
                        <p class="artist-name">${artists}</p>
                    `;
                    container.appendChild(box);
                });

            } catch (error) {
                console.error('Erro de conexão:', error);
                container.innerHTML = '<p>Erro de conexão com a API.</p>';
            }
        }

        async function loadCurrentTrack() {
            const container = document.getElementById('track-info'); 

            try {
                const response = await fetch(`${CURRENT_TRACK_URL}?PHPSESSID=${manualSessionId}`, {
                    method: 'GET',
                    credentials: 'include'
                });

                if (!response.ok || response.status === 204) {
                    if (response.status !== 204) {
                        const errorText = await response.text().catch(() => 'Erro desconhecido.');
                        console.error(`Erro ao buscar faixa atual. Status: ${response.status}`, errorText);
                    }
                    container.innerHTML = '<p>Nada tocando agora.</p>';
                    return;
                }

                const data = await response.json();

                if (!data?.item) { 
                    container.innerHTML = '<p>Nada tocando agora.</p>';
                    return;
                }

                const track = data.item;
                const imageUrl = track.album.images[0]?.url || '../static/imgs/profile-icon.png';
                const artists = track.artists.map(a => a.name).join(', ');

                container.innerHTML = `
                    <div class="background-playing"><img class="playing" src="${imageUrl}"></div>
                    <h3 style="margin: 0;">${track.name}</h3>
                    <p style="margin: 5px 0 0 0;">${artists}</p>
                `;

            } catch (error) {
                console.error('Erro ao carregar faixa atual:', error);
                container.innerHTML = '<p>Erro ao obter faixa atual.</p>';
            }
        }

        async function loadAndDrawDashboard() {
            const MAX_GENRES_DISPLAY = 8;
            try {
                const response = await fetch(`${MY_TOP_ARTISTS}?PHPSESSID=${manualSessionId}`, {
                    method: 'GET',
                    credentials: 'include'
                });

                const container = document.getElementById('myTopArtists');
                container.innerHTML = ''; 

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({})); 
                    console.error('Erro ao buscar genres:', errorData);
                    container.innerHTML = '<p>Vincule com a sua conta Spotify.</p>';
                    return;
                }

                const rawData = await response.json();
                
                const labels = Object.keys(rawData);
                const counts = Object.values(rawData);
                
                const topLabels = labels.slice(0, MAX_GENRES_DISPLAY);
                const topCounts = counts.slice(0, MAX_GENRES_DISPLAY);

                const maxCount = Math.max(...topCounts);

                topLabels.forEach((label, index) => {
                    const count = topCounts[index];
                    const percentage = (count / maxCount) * 100;
                    
                    const row = document.createElement('div');
                    row.className = 'bar-row';
                    
                    row.innerHTML = `
                        <div class="genre-label">${label}</div>
                        <div class="bar-chart-wrapper">
                            <div class="bar" style="width: ${percentage}%"></div>
                        </div>
                        <div class="bar-value">${count}</div>
                    `;
                    
                    container.appendChild(row);
                });

            } catch (error) {
                console.error("Erro ao carregar dados do endpoint:", error);
                document.getElementById('myTopArtists').innerHTML = 
                    "<p>Não foi possível carregar os dados de gênero.</p>";
            }
        }

        // window.addEventListener('load', async () => {

        //     const isAuthenticated = await checkAndLoadEssentialUserData();
    
        //     if (!isAuthenticated) {
        //         return; 
        //     }

        //     handleUrlError();

        //     await Promise.all([
        //         loadMyPlaylists(),
        //         loadRecentTracks(),
        //         // loadCurrentTrack(), // Se estiver ativa, inclua
        //         loadAndDrawDashboard()
        //     ]);

        //     // setInterval(loadCurrentTrack, 60000); 

        //     document.body.classList.add('loaded');
        // });

        window.addEventListener('load', async () => {
            const isAuthenticated = await checkAndLoadEssentialUserData();
            
            if (!isAuthenticated) {
                return; 
            }

            handleUrlError();

            const mainContent = document.getElementById('main');
            const linkSpotifyButtonId = 'vincular-spotify';
            
            if (isSpotifyLinked) {
                await Promise.all([
                    loadMyPlaylists(),
                    loadRecentTracks(),
                    // loadCurrentTrack(),
                    loadAndDrawDashboard()
                ]);
                
                // setInterval(loadCurrentTrack, 60000); 

            } else {
                // Se NÃO estiver vinculado
                mainContent.innerHTML = `
                    <div class="vinc">
                        <h2>Você ainda não vinculou sua conta ao Spotify.</h2>
                        <p style="margin-bottom: 30px;">Vincule agora para ver suas estatísticas!</p>
                        <button id="${linkSpotifyButtonId}">
                            Vincular ao Spotify
                        </button>
                    </div>
                `;
                
                document.getElementById(linkSpotifyButtonId).addEventListener('click', () => {
                    const manualSessionId = localStorage.getItem('manualSessionId');
                    if (manualSessionId) {
                        window.location.href = `http://127.0.0.1:8131/spotify/auth?PHPSESSID=${manualSessionId}`;
                    } else {
                        redirectToLogin();
                    }
                });
            }
            
            document.body.classList.add('loaded');
        });
    </script>

</body>
</html>