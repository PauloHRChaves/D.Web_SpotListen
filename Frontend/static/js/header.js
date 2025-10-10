// ⚠️*************** REUTILIZANDO FUNÇÃO (MOCK) **********************⚠️

// Função para verificar o status de login do usuário
async function checkLoginStatus() {
    const token = localStorage.getItem('authToken');

    if (!token) {
        document.body.classList.add('is-logged-out');
        document.body.classList.remove('is-logged-in');
        return false;
    }

    try {
        const response = await fetch('http://localhost:8000/logged-in', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (response.ok) {
            document.body.classList.add('is-logged-in');
            document.body.classList.remove('is-logged-out');
            return true;
        } else if (response.status === 401) {
            localStorage.removeItem('authToken');
            document.body.classList.add('is-logged-out');
            document.body.classList.remove('is-logged-in');
            return false;
        } else {
            document.body.classList.add('is-logged-out');
            document.body.classList.remove('is-logged-in');
            return false;
        }
    } catch (error) {
        console.error('Erro ao verificar status de login:', error);
        document.body.classList.add('is-logged-out');
        document.body.classList.remove('is-logged-in');
        return false;
    }
}

// ********************************************************************

// Função para ativar o link de navegação da página atual
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
        await Promise.all([checkLoginStatus()]);

        const profileLink = document.getElementById('auth-link');
        if (profileLink) {
            profileLink.addEventListener('click', (event) => {
                const token = localStorage.getItem('authToken');
                if (token) {
                } else {
                    event.preventDefault();
                    showToast();
                }
            });
        }
        
        activateNavLink();
        //setupLogout();

        const headerContainer = document.querySelector('.header-container');
        if (headerContainer) {
            headerContainer.classList.add('show');
        }

    } catch (error) {
        console.error('Erro fatal durante a inicialização:', error);
        document.body.classList.add('is-logged-out');
    }
});