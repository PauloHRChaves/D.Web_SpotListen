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
    <link rel="stylesheet" href="/static/css/index.css">
    <link rel="stylesheet" href="/static/css/carrossel.css">
    
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
        .sixth-container{
            height: 200px;
            /* background: #ffffffff; */
            background: linear-gradient(to bottom, #c0c0c0 0%, #ffffff 100%);
            position: relative;
            z-index: 10;
        }
        .footer {
            background:  #010e18ae;
            color: #fff;
            padding: 2rem 0;
            text-align: center;
            margin-top: 1.5rem;
        
            .social-icons {
                margin: 1rem 0;
                cursor: pointer;
                color: white;
            }
        }
    </style>
</head>
<body>
    <!--Cabeçalho de navegação-->
    <header id="header-placeholder"><?php include 'templates/header.php'; ?></header>

    <!--Todo conteúdo da pagina-->
    <main>
        <section class="first-container" id="home">
            <div class="first-content">
                <h1 class="intro-title">A Música Que Define<br>Seu Momento</h1>
            </div>
        </section>

        <section class="second-container">
            <div class="second-content">
                <div class="image3d">
                    <img src="/static/imgs/vibing.png" alt="...">
                </div>

                <div class="textcontent">
                    <p>Conecte sua conta do Spotify para desbloquear uma análise profunda de seu comportamento musical.
                        Descubra padrões ocultos, entenda as variáveis que tornam uma música um 'hit' pessoal e receba insights exclusivos sobre as evolução dos seus gostos ao longo do tempo. 
                        Sua biblioteca não é só uma lista de faixas é um conjunto de dados esperando serem revelados.
                    </p>
                    <a href="">Saiba mais</a>
                </div>
            </div>
        </section>

        <section class="third-container">
            <div class="third-content">
                <a href="/templates/genres.php?genre=pop" class="box box1">
                    <div class="box-bg"></div>
                    <h1>POP</h1>
                </a>
                <a href="/templates/genres.php?genre=rock" class="box box2">
                    <div class="box-bg"></div>
                    <h1>ROCK</h1>
                </a>
                <a href="/templates/genres.php?genre=hip%20hop" class="box box3">
                    <div class="box-bg"></div>
                    <h1>HIP HOP</h1>
                </a>
                <a href="/templates/genres.php?genre=electronic" class="box box4">
                    <div class="box-bg"></div>
                    <h1>Eletrônica</h1>
                </a>
                <a href="/templates/genres.php?genre=reggae" class="box box5">
                    <div class="box-bg"></div>
                    <h1>REGGAE</h1>
                </a>
                <a href="/templates/genres.php?genre=brazil%20mpb" class="box box6">
                    <div class="box-bg"></div>
                    <h1>MPB</h1>
                </a>
                <a href="/templates/genres.php?genre=classic" class="box box7">
                    <div class="box-bg"></div>
                    <h1>CLÁSSICA</h1>
                </a>
                <a href="/templates/genres.php?genre=indie" class="box box8">
                    <div class="box-bg"></div>
                    <h1>INDIE</h1>
                </a>
            </div>
        </section>

        <section class="showcarousel no" id="carousel">
            <h1 class="topweekly">Top da Semana</h1>
            <div class="carousel">     
                <div class="carousel-section">
                    <div class="carousel-wrapper" id="carousel-wrapper">

                    </div>
                </div>
            </div>
        </section>

        <section class="fifth-container">
            <div class="content">
                <div class="textcontent">
                    <h1>Titulo</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit placeat rem iure voluptates vel aperiam. 
                        Sunt soluta officia quidem dolorem.Numquam vero ipsum praesentium dicta veniam 
                        rem ratione molestias mollitia. Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut, maiores atque! 
                        Amet nobis quidem vitae molestiae. Sunt eligendi minus totam voluptatem doloribus non reiciendis quod dignissimos ad!
                        Fugiat, quibusdam minima.
                    </p>
                    <a href="">Saiba mais</a>
                </div>
                <div class="image3d">
                    <img src="/static/imgs/vibing4.png" alt="...">
                </div>
            </div>
        </section>

        <section class="sixth-container">
        </section>

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p>&copy; 2025. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6">
                        <a class="social-icons" target="_blank" href="">
                            <br>
                            <i class="fa-brands fa-github fa-2xl"></i>
                        </a>
                    </div>
                </div>
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
</body>
</html>