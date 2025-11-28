// =========================================================
// CONFIGURAÇÃO DE URI

const BACKEND_BASE_URL = 'http://127.0.0.1:8131';
// const BACKEND_BASE_URL = 'https://spotlisten-api.loca.lt';

const LOGOUT_ENDPOINT = `${BACKEND_BASE_URL}/logout`;
const LOGGED_IN_ENDPOINT = `${BACKEND_BASE_URL}/logged-in`;
const HEADER_HTML_PATH = '/templates/header.html';
const ROOT_PATH = '/';

// =========================================================

async function loadHeader() {
    const headerPlaceholder = document.getElementById('header-placeholder'); 
    
    const response = await fetch(HEADER_HTML_PATH); 
    const headerHtml = await response.text();
    headerPlaceholder.innerHTML = headerHtml;

}

function setupLogout() {
    const logoutButton = document.getElementById('logout-button');

    if (logoutButton) {
        logoutButton.addEventListener('click', async (event) => {
            event.preventDefault();

            try {
                const response = await fetch(LOGOUT_ENDPOINT, { 
                    method: 'POST',
                });

                if (response.ok) {
                    localStorage.removeItem('manualSessionId');
                    window.location.href = ROOT_PATH;
                } else {
                    console.error('Falha no logout do servidor:', response.status);
                    window.location.href = ROOT_PATH;
                }
            } catch (error) {
                console.error('Erro de rede ao fazer logout:', error);
                window.location.href = ROOT_PATH;
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
    
    const url = `${LOGGED_IN_ENDPOINT}?PHPSESSID=${manualSessionId}`; 

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
            // Se a API retornar erro 
            localStorage.removeItem('manualSessionId');
            document.body.classList.add('is-logged-out');
            document.body.classList.remove('is-logged-in');
            return false;
        }
    } catch (error) {
        // Erro de rede
        localStorage.removeItem('manualSessionId');
        document.body.classList.add('is-logged-out');
        document.body.classList.remove('is-logged-in');
        return false;
    }
}


function activateNavLink() {
    const links = document.querySelectorAll(".nav-link");
    const currentPath = window.location.pathname;

    links.forEach(link => {
        link.classList.remove("active");
        
        const linkPath = link.getAttribute("href");
        
        // Verifica se é a página atual ou a raiz (tratando linkPath="/templates/" como raiz)
        const isRootLink = linkPath === ROOT_PATH || linkPath === "/templates/";

        if (linkPath === currentPath || (isRootLink && currentPath === ROOT_PATH)) {
            link.classList.add("active");
        }
    });
}

document.addEventListener("DOMContentLoaded", async () => {
    try {
        // Checa o status 
        await checkLoginStatus(); 

        // Carrega o HTML do cabeçalho
        await loadHeader();
        
        // Ativa o link e configura o botão de logout
        activateNavLink();
        setupLogout();
        
        const headerContainer = document.querySelector('.header-container');
        if (headerContainer) {
            headerContainer.classList.add('show');
        }

    } catch (error) {
        console.error('Erro fatal durante a inicialização:', error);
        // Garante que o estado de deslogado seja o padrão em caso de erro
        document.body.classList.add('is-logged-out');
    }
});