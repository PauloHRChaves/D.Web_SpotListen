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
    
    <!--Inserir e estilizar cabeçalho na página-->
    <script src="../static/js/header.js"></script>
    <link rel="stylesheet" href="../static/css/header.css">

    <!--Bootstrap Icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

    <style>
        :root{
            --clamp: clamp(5rem, 4vw, 8rem );
        } 
        
        body {
            background: linear-gradient(0deg, var(--black) 0%, var(--second-background) 150%);
        }

        main {
            margin-top: 80px;
            flex-grow: 1;
            overflow-y: auto;
            padding: 0 0.5rem;
        }
        /* Scrollbar*/
        main::-webkit-scrollbar {
            width: clamp(0.1rem, 0.7vw, 0.5rem);
        }
        main::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--white) 0%, var(--black) 120%);
            border-radius: var(--radius);
        }
    </style>

    <style>
        .dashboard-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 2rem 4rem 0 4rem;
        }

        .dashboard-wrapper {
            width: 80%;
            border-radius: 1rem;
            min-width: 550px;
            color: #ffffff;
            background-color: #02172071;
            padding: 2rem 3rem;
            border: 2px solid #04455f5a;
            min-width: 1000px;
            
            .dashboard-grid{
                display: grid;
                grid-template-columns: 2fr 1fr;
                grid-template-areas:
                    "profile profile"
                    "favorite aside"
                    "favorite aside";
                gap: 2rem;

                .user-profile-section {
                    grid-area: profile;
                    display: flex;
                    gap: 1.5rem;
                    border-radius: 4rem;
                    margin-bottom: 2rem;
                

                    img {
                        border-radius: 50%;
                        border: 2px solid #021720;
                        object-fit: cover;
                    }
                }
                

                .favorite-section {
                    grid-area: favorite;
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;
                    padding: 1rem 1.5rem;
                    border-radius: 1rem;
                    border: 2px solid #04455f5a;
                    background-color: rgba(25, 73, 101, 0.34);

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
                            width: calc(4 * 150px + 3 * 1rem); /* Largura para 4 livros e 3 espaços de 1rem */
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
                        
                        .bookstar {
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
                
                .sidebar {
                    grid-area: aside;
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;

                    .currently-reading{
                        display: flex;
                        flex-direction: column;
                        gap: var(--radius);
                        align-items: center;
                        background-color: rgba(25, 73, 101, 0.34);
                        padding: 1rem 1.5rem;
                        border-radius: 1rem;
                        border: 2px solid #04455f5a;

                    }
                    .wish-list{
                        display: flex;
                        flex-direction: column;
                        gap: var(--radius);
                        align-items: center;
                        background-color: rgba(25, 73, 101, 0.34);
                        padding: 1rem 1.5rem;
                        border-radius: 1rem;
                        border: 2px solid #04455f5a;
                    }
                    
                }
            }
        }

    </style>
    
</head>
<body>
    <!-- Navigation -->
    <header id="header-placeholder"><?php include 'header.php'; ?></header>

    <!--Todo conteúdo da pagina-->
    <main>
        <div class="dashboard-page">
            <div class="dashboard-wrapper">
                <div class="dashboard-grid">
                    <section class="user-profile-section">
                        <img src="../static/imgs/profile-icon.png" alt="profile_pic" style="width: 8rem;height: 8rem;">
                        <div>
                            <h1><span id="username-display"></span></h1>
                        </div>
                    </section>

                    <section class="favorite-section">
                        <h2>Ouvidas Recentes</h2>
                        <div class="songcontent">
                            <i class="bi bi-arrow-left-short arrow-short" onclick="scrollBooks(-1)"></i>
                            <div class="scroller-container">
                                <div class="scroller-wrapper">
                                    <div class="bookstar">
                                        <div class="songsliked"></div>
                                        <h3 class="songname">songname 1</h3>
                                        <p class="songowner">Author 1</p>
                                    </div>
                                    <div class="bookstar">
                                        <div class="songsliked"></div>
                                        <h3 class="songname">songname 2</h3>
                                        <p class="songowner">Author 2</p>
                                    </div>
                                    <div class="bookstar">
                                        <div class="songsliked"></div>
                                        <h3 class="songname">songname 3</h3>
                                        <p class="songowner">Author 3</p>
                                    </div>
                                    <div class="bookstar">
                                        <div class="songsliked"></div>
                                        <h3 class="songname">songname 4</h3>
                                        <p class="songowner">Author 4</p>
                                    </div>
                                    <div class="bookstar">
                                        <div class="songsliked"></div>
                                        <h3 class="songname">songname 5</h3>
                                        <p class="songowner">Author 5</p>
                                    </div>
                                    <div class="bookstar">
                                        <div class="songsliked"></div>
                                        <h3 class="songname">songname 6</h3>
                                        <p class="songowner">Author 6</p>
                                    </div>
                                </div>
                            </div>
                            <i class="bi bi-arrow-right-short arrow-short" onclick="scrollBooks(1)"></i>
                        </div>
                    </section>
                
                    <aside class="sidebar">
                        <section class="currently-reading">
                            <h2>NONE</h2>
                            <div id="reading">
                                <!--conteudo-->
                            </div>
                        </section>
                        <section class="wish-list">
                            <h2>NONE</h2>
                            <div id="wishbook">
                                <!--conteudo-->
                            </div>
                        </section>
                    </aside>
                </div>
            </div>
        </div>
    </main>

    <footer></footer>

    <!--Scroll para favorite-section-->
    <script>
        function scrollBooks(direction) {
            const scroller = document.querySelector('.scroller-container');
            const bookWidth = 150;
            const gap = 16;
            const scrollAmount = (bookWidth + gap);
            scroller.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth'
            });
        }
    </script>

    <!-- USARIO LOGADO
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const token = localStorage.getItem('authToken');

            if (!token) {
                window.location.href = '/templates/auth/login.html';
                return;
            }
            try {
                const response = await fetch('http://localhost:8000/logged-in', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                const user = await response.json();

                if (response.ok) {
                    document.getElementById('username-display').textContent = user.USERNAME;
                } else {
                    window.location.href = 'templates/auth/login.html';
                }
            } catch (error) {
                console.error('Erro ao buscar dados do usuário:', error);
                window.location.href = 'templates/auth/login.html';
            }
        });
    </script>-->
    
</body>
</html>