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
                <h1 class="intro-title">A Música Que Define<br>Sua Vibe</h1>
                <p class="intro-subtitle">Por trás dos hits: veja playcounts, ouvintes e o que move a indústria.</p>
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

        <section class="fourth-container">
            <h1 class="topweekly">Marcados na História</h1> 
            <div class="goatgrid">
                <a href="/templates/artist-info.php?artist=The%20Beatles" class="content-item item-1" style="--bg-url: url('https://disconecta.com.br/wp-content/uploads/2025/04/the-beatles-credito-Apple-Corps-Ltd-e1744307427483.jpg');">
                    <h2>The Beatles</h2>
                </a>
                <a href="/templates/artist-info.php?artist=Elvis%20Presley" class="content-item item-2" style="--bg-url: url('https://segredosdomundo.r7.com/wp-content/uploads/2022/07/elvis-presley-15-curiosidade-sobre-o-rei-do-rock.jpg');">
                    <h2>Elvis</h2>
                </a>
                <a href="/templates/artist-info.php?artist=Aretha%20Franklin" class="content-item item-3" style="--bg-url: url('https://www.rollingstone.com/wp-content/uploads/2018/08/Aretha-Franklin-best-songs-2018-list-read.jpg');">
                    <h2>Aretha Franklin</h2>
                </a>
                <a href="/templates/artist-info.php?artist=Bob%20Dylan" class="content-item item-4" style="--bg-url: url('https://rollingstone.com.br/wp-content/uploads/bob_dylan_em_2012_foto__ap___chris_pizzello___file.jpg');">
                    <h2>Bob Dylan</h2>
                </a>
                <a href="/templates/artist-info.php?artist=Michael%20Jackson" class="content-item item-5" style="--bg-url: url('https://photo.kidzworld.com/images/2018131/6c72f59a-7cf0-42c4-9c49-a73b6fc1aa54/michael-jackson-child.jpg');">
                    <h2>Michael Jackson</h2>
                </a>
                <a href="/templates/artist-info.php?artist=Madonna" class="content-item item-6" style="--bg-url: url('https://i.abcnewsfe.com/a/59fc9d97-c5a7-4238-83b2-0710c6a45770/madonna-gty-jef-250930_1759252028617_hpMain_16x9.jpg?w=992');">
                    <h2>Madonna</h2>
                </a>
                <a href="/templates/artist-info.php?artist=James%20Brown" class="content-item item-7" style="--bg-url: url('https://taz.de/picture/2218850/1200/1727352.jpeg');">
                    <h2>James Brown</h2>
                </a>
                <a href="/templates/artist-info.php?artist=Queen" class="content-item item-8" style="--bg-url: url('https://www.hellen.design/wp-content/uploads/2018/11/blog-historia-logotipo-banda-queen-e1647721184935.jpg');">
                    <h2>Queen</h2>
                </a>
                <a href="/templates/artist-info.php?artist=Stevie%20Wonder" class="content-item item-9" style="--bg-url: url('https://www.estadao.com.br/resizer/v2/XGNTEV4QOVGWZDPBSW3CQ3BEKY.jpg?quality=80&auth=4223dd6b2c9c1e8857965514b842bf9342c5b4040fac0aef5adf0a11da29d7d5&width=1200&height=675&smart=true');">
                    <h2>Stevie Wonder</h2>
                </a>
                <a href="/templates/artist-info.php?artist=Chuck%20Berry" class="content-item item-10" style="--bg-url: url('https://ogimg.infoglobo.com.br/in/21082748-040-902/FT1086A/2008061384240.jpg');">
                    <h2>Chuck Berry</h2>
                </a>
                <a href="/templates/artist-info.php?artist=Bob%20Marley" class="content-item item-11" style="--bg-url: url('https://uploads.metroimg.com/wp-content/uploads/2016/05/11133915/bob-marley-reprodu%C3%A7%C3%A3o-internet.jpg');">
                    <h2>Bob Marley</h2>
                </a>
                <a href="/templates/artist-info.php?artist=Run%20DMC" class="content-item item-12" style="--bg-url: url('https://riffmedia.s3.us-east-2.amazonaws.com/wp-content/uploads/2018/05/29022510/run-dmc.jpg');">
                    <h2>Run DMC</h2>
                </a>
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