function toggleMenu() {
    const menu = document.querySelector('.navbar .menu');
    const hamburger = document.querySelector('.navbar .hamburger');
    const isHidden = menu.classList.toggle('hidden');
    hamburger.classList.toggle('active');
    menu.setAttribute('aria-hidden', isHidden);
}

document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.container'); // Seleziona il contenitore principale per il template
    const content = document.querySelector('.content'); // Seleziona l'area principale per il loader
    const loader = document.createElement('div');
    loader.className = 'content-loader';
    loader.style.display = 'none';
    loader.innerHTML = '<div class="spinner"></div>'; // Aggiungi il codice HTML dell'animazione del loader

    let loaderTimeout;

    function showLoader() {
        const loaderExist = document.querySelector('.content-loader');
        if (loaderExist) {
            loaderExist.remove();
        }
        content.appendChild(loader); // Aggiunge il loader all'interno di .content
        container.style.display = 'none';
        loader.style.display = 'flex';
    }

    function hideLoader() {
        const minDisplayTime = 100; // Tempo minimo in millisecondi

        const elapsedTime = Date.now() - loaderTimeout;
        const remainingTime = Math.max(0, minDisplayTime - elapsedTime);

        setTimeout(() => {
            loader.style.display = 'none';
            container.style.display = '';
        }, remainingTime);
    }

    function showError(message) {
        container.innerHTML = `<div class="error-message">${message}</div>`;
        container.style.display = '';
        loader.style.display = 'none';
    }

    function loadTemplate(templateName) {
        // Mostra il loader durante il caricamento del template
        loaderTimeout = Date.now();
        showLoader();

        // Rimuove i file CSS precedenti specifici del template
        document.querySelectorAll("link[rel='stylesheet'][data-template]").forEach(link => link.remove());

        // Aggiunge dinamicamente il file CSS
        if (templateName) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = `static/css/${templateName}.css`;
            link.setAttribute('data-template', templateName);
            link.onload = () => {
                console.log(`Loaded ${templateName}.css`);
            }
            document.head.appendChild(link);
        }

        // Rimuove i file JavaScript precedenti specifici del template
        document.querySelectorAll("script[data-template]").forEach(script => script.remove());

        // Aggiunge dinamicamente il file JavaScript
        if (templateName) {
            window.loadJS = undefined; // Resetta la funzione loadJS
            const script = document.createElement('script');
            script.src = `static/js/${templateName}.js`;
            script.defer = true;
            script.setAttribute('data-template', templateName);
            script.onload = () => {
                console.log(`Loaded ${templateName}.js`);
                if (typeof window.loadJS === 'function') {
                    window.loadJS();
                }
            }
            document.body.appendChild(script);
        }
    }

    function navigate(event) {

        const a_element = event.target.closest("a");
        if (a_element){
            const isFakeLink = a_element.getAttribute('data-fake') === 'true';

            if(isFakeLink) {
                event.preventDefault();
                return;
            }
        }

        const link = event.target.closest('.nav-link');
        if (!link || !link.hasAttribute('href')) return;

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('http')) return; // Ignora link esterni o ancore

        const isMobileLink= link.getAttribute('data-mobile') === 'true';
        const isLoaderDisabled = link.getAttribute('data-loader') === 'false';

        if(isMobileLink) {
            toggleMenu();
        }

        event.preventDefault();


        loaderTimeout = Date.now();
        if (isLoaderDisabled) {
            showLoader();
        }

        fetch(href, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'component': true
            },
        })
            .then(response => {
                if (response.redirected) {
                    // Gestisce il redirect
                    window.location.href = response.url;
                    return Promise.reject('Redirecting...');
                }

                const templateName = response.headers.get('templateName');
                return response.text().then(html => ({ html, templateName, href }));
            })
            .then(({ html, templateName, href }) => {
                container.innerHTML = html;
                window.history.pushState(null, '', href); // Aggiorna il link nella barra degli indirizzi
                loadTemplate(templateName);
            })
            .catch(error => {
                if (error !== 'Redirecting...') {
                    console.error('Error loading page:', error);
                    showError('Errore di connessione. Assicurati di essere online e riprova.');
                }
            })
            .finally(() => {
                hideLoader();
            });
    }

    document.body.addEventListener('click', navigate);
});
