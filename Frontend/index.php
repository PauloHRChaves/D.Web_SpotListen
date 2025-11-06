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
    <script src="/static/js/header.js"></script>
    <link rel="stylesheet" href="/static/css/header.css">

    <!--Bootstrap Icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

    <!--JS da página-->
    <script src="/static/js/carousel.js"></script>

    <!--CSS da página-->
    
    <style>
        .toastify{
            box-shadow: none;
            font-size: clamp(0.5rem, 1vw, 1.2rem);
            border-radius: var(--radius);
            font-family: "Work Sans", sans-serif;
            font-weight: 600;
        }
    </style>
    
    <style>
        @font-face {
            font-family: 'Greek';
            src: url('static/fonts/dalek_pinpoint/DalekPinpointBold.ttf') format('truetype');
        }
        :root {
            --color-primary: #1ED760;
            --color-background: #000000;
            --color-card-bg: #1A1A1A;
            --color-text-main: #FFFFFF;
            --color-text-secondary: #d2d2d2ff;
            --color-accent-cta: linear-gradient(90deg, #1DD760, #1ED7B7);
            --color-active-line: #1DB954;
            --font-display: 'Work Sans', sans-serif;
            --radius: 8px;
        }

        /* --- ESTILOS GERAIS E LAYOUT --- */
        body {
            background-color: var(--color-background);
            font-family: var(--font-display);
            display: flex;
            flex-direction: column;
            background-attachment: fixed;
            background-size: cover;
            color: var(--color-text-main);
        }

        main {
            margin-top: 80px;
            flex-grow: 1;
            overflow-y: auto;
            /* background: radial-gradient(circle at top left, #c5c5c5ff, transparent 20%), radial-gradient(circle at bottom right, #aca6ffff, transparent 20%), 
                radial-gradient(circle at 24px 340px, #a6ffdaff, transparent 20%),
                  #00131bff; */
        }

        /* Scrollbar personalizado */
        main::-webkit-scrollbar {
            width: clamp(0.1rem, 0.7vw, 0.5rem);
        }
        main::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #c0c0c0 20%, white 50%);
            border-radius: var(--radius);
        }

        .section-title {
            font-family: var(--font-display);
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 700;
            color: var(--color-text-main);
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
            
        }

        /* --- HERO SECTION --- */
        .hero-container {
            position: relative;
            display: flex;
            /* align-items: center; */
            justify-content: end;
            padding: 40px 0;
            min-height: 70vh;
            overflow: hidden;

            background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.4)), url("static/imgs/background_ex.png");
            background-position: center;
            background-size: cover;
            box-shadow: inset 0 -30px 30px rgb(0 0 0);
        }

        .hero-content {
            display: flex;
            align-items: center;
            justify-content: end;
            gap: 100px;
            width: 100%;
            padding: 0 8%;
            position: relative;
            z-index: 2;
        }

        .image3d img {
            width: clamp(350px, 30vw, 500px); 
            height: auto;
            display: block;
            filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.15));
            border-right:1px solid;
        }

        .text-cta-content {
            max-width: 805px;
            color: var(--color-text-main);
            text-align: left;
        }

        .intro-title {
            font-family: var(--font-display);
            font-size: clamp(3.5rem, 6vw, 4.5rem);
            font-weight: 900;
            margin-bottom: 20px;
            line-height: 1.1;
            text-transform: uppercase;
            background: linear-gradient(135deg, var(--white) 30%, var(--secundary) 180%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
            text-shadow: 0 4px 12px rgba(255, 255, 255, 0.144);
            
        }

        .intro-title strong {
            color: var(--color-active-line);
            text-shadow: 0 0 15px rgba(29, 185, 84, 0.5);
        }

        .intro-text {
            font-size: clamp(1.15rem, 1.3vw, 1.5rem);
            color: var(--color-text-secondary);
            margin-bottom: 50px;
            line-height: 1.6;
        }
        .intro-text strong {
            color: var(--color-text-main);
            font-weight: 700;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 20px 45px;
            border-radius: 50px;
            background: var(--color-accent-cta); 
            color: var(--color-text-main); 
            text-decoration: none;
            font-weight: 700;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: transform 0.3s, box-shadow 0.3s, filter 0.3s;
            box-shadow: 0 0 20px rgba(29, 185, 84, 0.4); 
            width: 100%;
        }

        .cta-button:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 0 10px rgba(29, 185, 84, 0.7);
            filter: brightness(1.1);
        }

        .cta-button i {
            font-size: 1.4rem;
        }

        .secondary-link {
            display: block;
            margin-top: 15px;
            color: var(--color-text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.2s;
            justify-self: end;
        }

        .secondary-link:hover {
            color: var(--color-active-line);
        }

        .hero-background-fade {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, var(--color-background) 10%, transparent 100%);
            z-index: 1;
        }

        .third-container {
            padding: 60px 5%;
            margin: 0 auto;
            text-align: center;
            display: flex;
            gap: 3rem;
            justify-content: center;
        }

        .genres-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
            max-width: 1200px;
        }

        .genre-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            text-decoration: none;
            background-color: var(--color-card-bg);
            border-radius: var(--radius);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s, background-color 0.2s, box-shadow 0.2s, border 0.2s;
            min-height: 150px;
            border: 2px solid transparent;
        }

        .genre-card i {
            font-size: 2.5rem;
            color: var(--color-text-secondary);
            margin-bottom: 10px;
            transition: color 0.2s;
        }

        .genre-card h3 {
            color: var(--color-text-main);
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            text-transform: uppercase;
        }

        .genre-card:hover {
            transform: translateY(-5px);
            background-color: #2e2e2e;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }
        .genre-card:hover i {
            color: var(--color-active-line);
        }

        .topweekly {
            text-align: center;
            position: absolute;
            left: 30%;
            top: -2%;
            right: 30%;
            font-size: 7rem;
            z-index: 5;
            opacity: 0.8;
            font-family: sans-serif;
        }
        .topweekly.track {
            top: -2%;
            z-index: -5;
        }

        .fifth-container {
            padding: 80px 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .fifth-container .content {
            display: flex;
            align-items: center;
            gap: 50px;
            flex-direction: row-reverse;
        }

        .fifth-container .textcontent {
            max-width: 50%;
        }

        .fifth-container h1 {
            font-size: clamp(2rem, 3vw, 3.5rem);
            color: var(--color-text-main);
            font-weight: 800;
            margin-bottom: 20px;
        }

        .fifth-container p {
            font-size: 1.1rem;
            color: var(--color-text-secondary);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .fifth-container .image3d img {
            width: 100%;
            max-width: 400px;
            height: auto;
            filter: drop-shadow(0 0 15px rgba(215, 30, 215, 0.2));
        }

        .sixth-container {
            height: 100px;
            background: linear-gradient(to top, #010e18ae 0%, var(--color-background) 100%);
            position: relative;
            z-index: 10;
        }

        .footer {
            background: #00243fae;
            color: #fff;
            padding: 2rem 0;
            text-align: center;
            margin-top: 0;
        }
        .footer .copyright-text{
            font-size: 1rem;
            color: var(--color-text-main);
            transition: color 0.2s;
        }
        .footer .social-icons {
            font-size: 2.5rem;
            color: var(--color-text-main);
            transition: color 0.2s;
        }
        .footer .social-icons:hover {
            color: var(--color-active-line);
        }

        .toastify{
            box-shadow: none;
            font-size: clamp(0.5rem, 1vw, 1.2rem);
            border-radius: var(--radius);
            font-family: var(--font-display);
            font-weight: 600;
        }

        @media (max-width: 1024px) {
            .hero-content {
                flex-direction: column;
                text-align: center;
                gap: 50px;
            }
            .image3d img {
                width: clamp(250px, 40vw, 400px);
            }
            .text-cta-content {
                max-width: 80%;
                text-align: center;
            }
            .cta-button {
                padding: 15px 30px;
                font-size: 1rem;
            }
            .fifth-container .content {
                flex-direction: column-reverse;
                text-align: center;
            }
            .fifth-container .textcontent, .fifth-container .image3d {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .intro-title {
                font-size: clamp(2.5rem, 8vw, 4rem);
            }
            .intro-text {
                font-size: 1rem;
            }
            .section-title {
                font-size: clamp(1.5rem, 5vw, 2rem);
            }
            .genres-grid {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }
            .genre-card {
                min-height: 120px;
                padding: 15px;
            }
            .genre-card i {
                font-size: 2rem;
            }
            .genre-card h3 {
                font-size: 1rem;
            }
            .fifth-container h1 {
                font-size: clamp(1.8rem, 6vw, 2.5rem);
            }
        }
    </style>
    
    <style>
        /* Carrossel CSS (Ajustado) */
        :root {
            --carousel-height: 580px;
            --carousel-width: 80vw;
            --transition-time: 0.5s;
        }
        .showcarousel.no{
            opacity: 0;
            height: 2px;
        }

        .showcarousel {
            opacity: 1;
            position: relative;
            z-index: 1;
            margin-top: 2rem;
            transition: opacity 1s ease-in-out;
            background: radial-gradient(circle at 50%, #ffffffff 50px, #000000 200px);
            height: 550px;
        }

        .carousel {
            position: relative;
            height: var(--carousel-height);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 0;
        }

        .carousel-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .carousel-item {
            position: absolute;
            top: 50%;
            transform: scale(0.7);
            opacity: 0.3;
            transition: transform var(--transition-time) ease, opacity var(--transition-time);
            cursor: pointer;
        }

        .carousel-item img {
            border-radius: 16px;
            width: 280px;
            height: 380px;
            object-fit: cover;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        }

        .carousel-item.position-center {
            transform: translateY(-50%) scale(1);
            opacity: 0.95;
            z-index: 5;
            margin: 0 1rem;

            img{
                min-width: 310px
            }
        }

        /* Posição -1 e 1 */
        .carousel-item.position-left-1 {
            transform: translate(-290px, -50%) scale(0.85);
            opacity: 0.7;
            z-index: 4;
        }

        .carousel-item.position-right-1 {
            transform: translate(290px, -50%) scale(0.85);
            opacity: 0.7;
            z-index: 4;
        }

        /* Posição -2 e 2 */
        .carousel-item.position-left-2 {
            transform: translate(-520px, -50%) scale(0.75);
            opacity: 0.5;
            z-index: 3;
        }

        .carousel-item.position-right-2 {
            transform: translate(520px, -50%) scale(0.75);
            opacity: 0.5;
            z-index: 3;
        }

        /* NOVO: Posição -3 e 3 */
        .carousel-item.position-left-3 {
            transform: translate(-730px, -50%) scale(0.65);
            opacity: 0.2;
            z-index: 2;
        }

        .carousel-item.position-right-3 {
            transform: translate(730px, -50%) scale(0.65);
            opacity: 0.2;
            z-index: 2;
        }

        /* Itens totalmente fora da vista */
        .carousel-item.position-far-left,
        .carousel-item.position-far-right {
            opacity: 0;
            pointer-events: none;
        }

        .highlight-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            margin-left: 0;
            opacity: 0;
            pointer-events: none;
            transition: opacity var(--transition-time);
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.2) 70%, transparent); /* Gradiente mais opaco */
            padding: 25px 20px; 
            border-radius: 0 0 16px 16px;
            backdrop-filter: blur(2px);
            color: var(--color-text-main);
            z-index: 6;
            box-sizing: border-box;
        }

        .position-center .highlight-info {
            opacity: 1;
            pointer-events: auto;
        }

        .highlight-info h1 {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0px 3px 6px black;
        }

        .highlight-info p {
            font-size: 0.95rem;
            color: var(--color-text-secondary); 
            margin: 3px 0;
        }

        .highlight-info button {
            background: var(--color-active-line); 
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 50px; 
            cursor: pointer;
            margin-top: 15px;
            font-weight: 600;
            transition: background 0.2s;
        }

        .highlight-info button:hover {
            background: #1DB954;    
        }

        .nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.5);
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            z-index: 10;
            border-radius: 50%;
            padding: 8px;
            transition: background 0.3s;
        }

        .nav:hover {
            background: rgba(0,0,0,0.8);
        }

        .nav.prev { left: 10px; }
        .nav.next { right: 10px; }
    </style>

    <style>
        .top-tracks-section.no{
            opacity: 0;
            height: 2px;
        }
        .top-tracks-section {
            opacity: 1;
            position: relative;
            z-index: 1;
            transition: opacity 1s ease-in-out;
            padding: 60px 5%;
            padding-bottom: 100px;
        }

        .track-list-wrapper-container {
            max-width: 1800px;
            margin: 0 auto;
        }

        .track-list-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
            gap: 30px;
            padding-top: 30px;
        }

        .track-item {
            background-color: rgba(30, 30, 30, 0.7);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            transition: background-color 0.3s, transform 0.2s;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            height: auto;
        }

        .track-item:hover {
            background-color: rgba(40, 40, 40, 0.9);
            transform: translateY(-5px);
        }

        .track-header {
            display: flex;
            align-items: flex-start;
            width: 100%;
            margin-bottom: 15px;
        }

        .track-item-image {
            width: 80px;
            height: 80px;
            border-radius: 4px;
            object-fit: cover;
            margin-right: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            flex-shrink: 0;
        }

        .track-info-main {
            flex-grow: 1;
        }

        .track-info-main h1 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--color-text-main, #fff);
            margin: 0;
            
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            white-space: normal;
        }

        .track-metrics {
            width: 100%;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 15px;
        }

        .track-metric-item {
            font-size: 0.9rem;
            color: var(--color-text-secondary, #ccc);
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }

        .metric-value {
            font-weight: 600;
            color: var(--color-active-line, #1DB954);
        }

        .track-item button {
            background: var(--color-active-line); 
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 50px; 
            cursor: pointer;
            margin-top: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            align-self: flex-end;
            transition: background 0.2s;
        }

        .track-item button:hover {
            background: #1DB954;
        }
    </style>

    <style>
        .top15{
            margin-top: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }
        .top15 img{
            width: 400px;
        }
        .top15 h1{
            font-family: 'Greek';
            font-size: 6rem;
            color: #bebebeff;
        }
    </style>
</head>
<body>
    <!--Cabeçalho de navegação-->
    <header id="header-placeholder"><?php include 'templates/header.php'; ?></header>

    <!--Todo conteúdo da pagina-->
    <main>
        <section class="hero-container" id="home">
            <div class="hero-content">
                <div class="text-cta-content">
                    <h1 class="intro-title">A MÚSICA QUE DEFINE SEU MOMENTO</h1>
                    
                    <p class="intro-text">
                        Receba insights 
                        e descubra padrões que definem seus gostos musicais ao longo do tempo.
                    </p>
                    
                    <a href="templates/auth/login.html" class="cta-button pulse-effect">
                        <i class="bi bi-spotify"></i> CONECTAR COM SPOTIFY
                    </a>
                    
                    <a href="" class="secondary-link">Saiba mais</a>
                </div>
            </div>
            <div class="hero-background-fade"></div>
        </section>

        <section class="third-container">
            <div style="max-width: 700px;">
                <h2 class="section-title">GÊNEROS EM DESTAQUE</h2>
                <p style="text-align:justify; text-align: justify; font-size: clamp(0.5rem, 2vw, 1.2rem);">Sua referência rápida para os sons mais populares. 
                    Estes GÊNEROS EM DESTAQUE são as categorias musicais com maior número de ouvintes e relevância histórica. 
                    Escolha entre Pop, Rock, Hip Hop, Eletrônica, MPB, Classic, Reggae ou Indie e acesse imediatamente o que há de melhor em cada estilo!</p>
            </div>
            <hr>
            <div class="genres-grid">
                <a href="/templates/genres.php?genre=pop" class="genre-card">
                    <i class="bi bi-star-fill"></i>
                    <h3>POP</h3>
                </a>
                <a href="/templates/genres.php?genre=rock" class="genre-card">
                    <i class="bi bi-headphones"></i>
                    <h3>ROCK</h3>
                </a>
                <a href="/templates/genres.php?genre=hip%20hop" class="genre-card">
                    <i class="bi bi-boombox-fill"></i>
                    <h3>HIP HOP</h3>
                </a>
                <a href="/templates/genres.php?genre=electronic" class="genre-card">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <h3>Eletrônica</h3>
                </a>
                <a href="/templates/genres.php?genre=reggae" class="genre-card">
                    <i class="bi bi-peace"></i>
                    <h3>Reggae</h3>
                </a>
                <a href="/templates/genres.php?genre=brazil%20mpb" class="genre-card">
                    <i class="bi bi-music-player-fill"></i>
                    <h3>MPB</h3>
                </a>
                <a href="/templates/genres.php?genre=classic" class="genre-card">
                    <i class="bi bi-music-note-list"></i>
                    <h3>Classic</h3>
                </a>
                <a href="/templates/genres.php?genre=indie" class="genre-card">
                    <i class="bi bi-play-circle"></i>
                    <h3>Indie</h3>
                </a>
                </div>
        </section>

        <hr>

        <div class="top15">
            <img src="static/imgs/top15.png" alt="top15"> 
            <h1>SEMANAL</h1>
        </div>

        <section class="showcarousel no" id="carousel">
            <h2 class="section-title topweekly">Artistas</h2>
            <div class="carousel"> 
                <div class="carousel-section">
                    <div class="carousel-wrapper" id="carousel-wrapper">
                        </div>
                </div>
            </div>
        </section>

        <section class="section-container no top-tracks-section" id="top-tracks-section">
            <h2 class="section-title topweekly track">Tracks</h2>
            <div class="track-list-wrapper-container"> 
                <div class="track-list-wrapper" id="track-list-wrapper">
                    </div>
            </div>
        </section>

        <hr>

        <section class="fifth-container">
            <div class="content">
                <div class="textcontent">
                    <h1>RECURSOS EXCLUSIVOS</h1>
                    <p>Conecte seus artistas para buscar uma análise aprofundada de seu repertório.</p>
                </div>
                <div class="image3d">
                    <img src="/static/imgs/vibing4.png" alt="Arte abstrata musical">
                </div>
            </div>
        </section>
        
        <section class="sixth-container"></section>

        <footer class="footer">
            <div class="container">
                <a class="social-icons" target="_blank" href="">
                    <i class="bi bi-github"></i>
                    <p class="copyright-text">&copy; 2025. All Rights Reserved.</p>
                </a>
            </div>
        </footer>
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
        document.addEventListener('DOMContentLoaded', (event) => {
            const carouselWrapper = document.querySelector('.carousel-wrapper');
            
            if (carouselWrapper) {
                carouselWrapper.classList.add('fade-in');
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const wrapper = document.getElementById('track-list-wrapper');
            const section = document.getElementById('top-tracks-section');

            try {
                const response = await fetch('http://127.0.0.1:8131/lasfm/top15tracks');
                const data = await response.json();

                section.classList.remove('no');
                
                // Função de formatação para Playcount e Listeners (Ex: 1.6M, 783.3K)
                const formatNumber = (num) => {
                    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
                    if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
                    return num;
                };

                data.forEach((track, index) => {
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
                    wrapper.appendChild(item);
                });
            } catch (error) {
                console.error("Erro ao carregar top tracks:", error);
                section.style.display = 'none';
            }
        });
    </script>
</body>
</html>