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
                <h1 class="intro-title">TITLE</h1>
                <p class="intro-subtitle">SUBTITLE!</p>
            </div>

            <!--Barra de busca (AINDA N IMPLEMENTADO)-->
            <div class="search-container">
                <div class="search-bar">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="placeholder...">
                    <button class="search-btn" id="searchBtn" style="background: none; border: none; font-size: 2rem;">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </section>

        <section class="carousel">
            <div class="carousel-section">
                <div class="carousel-wrapper">
                    <div class="carousel-item">
                        <div class="img-cover">
                            <img src="/static/imgs/Blank_img.png" alt="...">
                        </div>
                        <div class="highlight-content">
                            <div class="img-cover-large">
                                <img src="/static/imgs/Blank_img.png" alt="...">
                            </div>
                            <div class="highlight-info">
                                <p class="author">NAME OWNER</p>
                                <h1 style="font-family: '';">NAME TITLE</h1>
                                <p class="synopsis">DESCRIPTION</p>
                                <button>Saiba mais..</button>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <div class="img-cover">
                            <img src="/static/imgs/Blank_img.png" alt="...">
                        </div>
                        <div class="highlight-content">
                            <div class="img-cover-large">
                                <img src="/static/imgs/Blank_img.png" alt="...">
                            </div>
                            <div class="highlight-info">
                                <p class="author">NAME OWNER</p>
                                <h1 style="font-family: '';">NAME TITLE</h1>
                                <p class="synopsis">DESCRIPTION</p>
                                <button>Saiba mais..</button>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <div class="img-cover">
                            <img src="/static/imgs/Blank_img.png" alt="...">
                        </div>
                        <div class="highlight-content">
                            <div class="img-cover-large">
                                <img src="/static/imgs/Blank_img.png" alt="...">
                            </div>
                            <div class="highlight-info">
                                <p class="author">NAME OWNER</p>
                                <h1 style="font-family: '';">NAME TITLE</h1>
                                <p class="synopsis">DESCRIPTION</p>
                                <button>Saiba mais..</button>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <div class="img-cover">
                            <img src="/static/imgs/Blank_img.png" alt="...">
                        </div>
                        <div class="highlight-content">
                            <div class="img-cover-large">
                                <img src="/static/imgs/Blank_img.png" alt="...">
                            </div>
                            <div class="highlight-info">
                                <p class="author">NAME OWNER</p>
                                <h1 style="font-family: '';">NAME TITLE</h1>
                                <p class="synopsis">DESCRIPTION</p>
                                <button>Saiba mais..</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="carousel-item">
                        <div class="img-cover">
                            <img src="/static/imgs/Blank_img.png" alt="...">
                        </div>
                        <div class="highlight-content">
                            <div class="img-cover-large">
                                <img src="/static/imgs/Blank_img.png" alt="...">
                            </div>
                            <div class="highlight-info">
                                <p class="author">NAME OWNER</p>
                                <h1 style="font-family: '';">NAME TITLE</h1>
                                <p class="synopsis">DESCRIPTION</p>
                                <button>Saiba mais..</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-button prev" onclick="prevBook()"><i class="bi bi-caret-left"></i></button>
                <button class="carousel-button next" onclick="nextBook()"><i class="bi bi-caret-right"></i></button>
            </div>
        </section>

        <section class="second-container">
            <div class="second-content">
                <div class="image3d">
                    <img src="/static/imgs/vibing.png" alt="...">
                </div>

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
            </div>
        </section>

        <section class="third-container">
            <div class="third-content">
                <div class="box box1">
                    <div class="box-bg"></div>
                    <h1>TITLE</h1>
                </div>
                <div class="box box2">
                    <div class="box-bg"></div>
                    <h1>TITLE</h1>
                </div>
                <div class="box box3">
                    <div class="box-bg"></div>
                    <h1>TITLE</h1>
                </div>
            </div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

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