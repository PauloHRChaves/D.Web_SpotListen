function setupLogout() {
    const logoutButton = document.getElementById('logout-button');

    if (logoutButton) {
        logoutButton.addEventListener('click', async (event) => {
            event.preventDefault();

            try {
                const response = await fetch('http://localhost:8131/logout', { 
                    method: 'POST',
                });

                if (response.ok) {
                    localStorage.removeItem('manualSessionId');
                    window.location.href = '/';
                } else {
                    console.error('Falha no logout do servidor:', response.status);
                    window.location.href = '/';
                }
            } catch (error) {
                console.error('Erro de rede ao fazer logout:', error);
                window.location.href = '/';
            }
        });
    }
}

async function checkLoginStatus() {
    const manualSessionId = localStorage.getItem('manualSessionId');
    if (!manualSessionId) {
        document.body.classList.add('is-logged-out');
        return false;
    }
    
    const url = `http://localhost:8131/logged-in?PHPSESSID=${manualSessionId}`;
    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (response.ok) {
            document.body.classList.add('is-logged-in');
            document.body.classList.remove('is-logged-out');
            return true;
        } else {
            localStorage.removeItem('manualSessionId');
            document.body.classList.add('is-logged-out');
            document.body.classList.remove('is-logged-in');
            return false;
        }
        
    } catch (error) {
        localStorage.removeItem('manualSessionId');
        document.body.classList.add('is-logged-out');
        document.body.classList.remove('is-logged-in');
        return false;
    }
}
// ********************************************************************

function activateNavLink() {
    const links = document.querySelectorAll(".nav-link");
    const currentPath = window.location.pathname; 
    console.log('Path atual do navegador:', currentPath);

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