<div class="header-container" id="header">
    <nav class="nav-container">
        <a class="logo" style="text-decoration: none; color: inherit;">
            <img src="/static/imgs/logo.png" alt="SpotListen" >
        </a>

        <ul class="navbar" style="list-style-type: none;">
            <li><a href="/" class="nav-link active">Início</a></li>
            <li><a href="/templates/genres.php" class="nav-link">Categorias</a></li>
            <li><a href="#" class="nav-link">y</a></li>
            <li><a href="/templates/profile.php" id="auth-link" class="nav-link">Perfil</a></li>
        </ul>

        <a class="nav-actions logged-out-only" href="/">
            <button class="btn-primary">
                <i class="bi bi-person-fill"></i>
                <span>Sign in</span>
            </button>
        </a>

        <a class="nav-actions logged-in-only">
            <button class="btn-secundary" id="logout-button">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </a>
    </nav>
</div>