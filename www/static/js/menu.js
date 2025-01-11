document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.container');
    const content = document.querySelector('.content');
    const loader = document.createElement('div');
    loader.className = 'content-loader';
    loader.style.display = 'none';
    loader.innerHTML = '<div class="spinner"></div>';

    let loaderTimeout;

    function showLoader() {
        const loaderExist = document.querySelector('.content-loader');
        if (loaderExist) {
            loaderExist.remove();
        }
        content.appendChild(loader);
        container.style.display = 'none';
        loader.style.display = 'flex';
    }

    function hideLoader() {
        const minDisplayTime = 100;

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
        document.querySelectorAll("link[rel='stylesheet'][data-template]").forEach(link => link.remove());

        if (templateName) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = `static/css/${templateName}.css`;
            link.setAttribute('data-template', templateName);
            link.onload = () => console.log(`Loaded ${templateName}.css`);
            document.head.appendChild(link);
        }

        document.querySelectorAll("script[data-template]").forEach(script => script.remove());

        if (templateName) {
            window.loadJS = undefined;
            const script = document.createElement('script');
            script.src = `static/js/${templateName}.js`;
            script.defer = true;
            script.setAttribute('data-template', templateName);
            script.onload = () => {
                console.log(`Loaded ${templateName}.js`);
                if (typeof window.loadJS === 'function') {
                    window.loadJS();
                }
            };
            document.body.appendChild(script);
        }
    }

    function navigateTo(href, isLoaderDisabled = false) {
        loaderTimeout = Date.now();
        if (!isLoaderDisabled) {
            showLoader();
        }

        fetch(href, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'component': true,
            },
        })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                    return Promise.reject('Redirecting...');
                }

                const templateName = response.headers.get('templateName');
                return response.text().then(html => ({ html, templateName, href }));
            })
            .then(({ html, templateName, href }) => {
                container.innerHTML = html;
                window.history.pushState({ templateName, href }, '', href);
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

    function navigate(event) {
        const link = event.target.closest('.nav-link');
        if (!link || !link.hasAttribute('href')) return;

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('http')) return;

        const isMobileLink = link.getAttribute('data-mobile') === 'true';
        const isLoaderDisabled = link.getAttribute('data-loader') === 'false';

        if (isMobileLink) {
            toggleMenu();
        }

        event.preventDefault();
        navigateTo(href, isLoaderDisabled);
    }

    function handlePopState(event) {
        if (event.state) {
            const { href, templateName } = event.state;
            if (href && templateName) {
                loaderTimeout = Date.now();
                showLoader();

                fetch(href,
                    {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'component': true,
                        },
                    })
                    .then(response => response.text())
                    .then(html => {
                        container.innerHTML = html;
                        loadTemplate(templateName);
                    })
                    .catch(error => {
                        console.error('Error loading page:', error);
                        showError('Errore di connessione. Assicurati di essere online e riprova.');
                    })
                    .finally(() => {
                        hideLoader();
                    });
            }
        }
    }

    document.body.addEventListener('click', navigate);
    window.addEventListener('popstate', handlePopState);
});
