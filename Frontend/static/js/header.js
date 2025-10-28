function setupLogout() {
    const logoutButton = document.getElementById('logout-button');

    if (logoutButton) {
        logoutButton.addEventListener('click', async (event) => {
            event.preventDefault();

            try {
                const response = await fetch('http://localhost:8131/logout', { 
                    method: 'POST',
                    credentials: 'include'
                });

                if (response.ok) {
                    window.location.href = '/templates/index.php';
                } else {
                    console.error('Falha no logout do servidor:', response.status);
                    window.location.href = '/templates/index.php';
                }
            } catch (error) {
                console.error('Erro de rede ao fazer logout:', error);
                window.location.href = '/templates/index.php';
            }
        });
    }
}

async function checkLoginStatus() {
    try {
        const response = await fetch('http://localhost:8131/logged-in', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
            credentials: 'include'
        });

        if (response.ok) {
            // Logado
            document.body.classList.add('is-logged-in');
            document.body.classList.remove('is-logged-out');
            return true;
        } 
        
        // Não Logado
        document.body.classList.add('is-logged-out');
        document.body.classList.remove('is-logged-in');
        return false;
        
    } catch (error) {
        console.error('Erro ao verificar status de login:', error);
        document.body.classList.add('is-logged-out');
        document.body.classList.remove('is-logged-in');
        return false;
    }
}
// ********************************************************************

function activateNavLink() {
    const links = document.querySelectorAll(".nav-link");
    const currentPath = window.location.pathname; 

    links.forEach(link => {
        link.classList.remove("active");
        
        const linkPath = link.getAttribute("href");

        if (linkPath === currentPath || (linkPath === "/templates/" && currentPath === "/")) {
            link.classList.add("active");
        }
    });
}

document.addEventListener("DOMContentLoaded", async () => {
    try {
        await checkLoginStatus();

        const profileLink = document.getElementById('auth-link');
        if (profileLink) {
            profileLink.addEventListener('click', (event) => {
            });
        }
        
        activateNavLink();
        setupLogout();

        const headerContainer = document.querySelector('.header-container');
        if (headerContainer) {
            headerContainer.classList.add('show');
        }

    } catch (error) {
        console.error('Erro fatal durante a inicialização:', error);
        document.body.classList.add('is-logged-out');
    }
});