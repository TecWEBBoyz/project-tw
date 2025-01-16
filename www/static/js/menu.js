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

    function loadTemplate(templateName, templateTitle) {
        document.querySelectorAll("link[rel='stylesheet'][data-template]").forEach(link => link.remove());

        if (templateTitle) {
            document.title = templateTitle;
        }

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

    function saveMenuState() {
        const menu = document.querySelector('.navbar .menu');
        const isHidden = menu.classList.contains('hidden');
        localStorage.setItem('menuState', isHidden ? 'hidden' : 'visible');
    }

    function restoreMenuState() {
        const menu = document.querySelector('.navbar .menu');
        const savedState = localStorage.getItem('menuState');
        if (savedState === 'hidden') {
            menu.classList.add('hidden');
        } else {
            menu.classList.remove('hidden');
        }
    }

    function clearMenuState() {
        localStorage.removeItem('menuState');
    }

    function toggleMenu() {
        const menu = document.querySelector('.navbar .menu');
        const hamburger = document.querySelector('.navbar .hamburger');
        const isHidden = menu.classList.toggle('hidden');
        hamburger.classList.toggle('active');
        menu.setAttribute('aria-hidden', isHidden);
        saveMenuState(); // Save the menu state
    }

    window.toggleMenu = toggleMenu;

    function hideMenu() {
        const menu = document.querySelector('.navbar .menu');
        const hamburger = document.querySelector('.navbar .hamburger');
        menu.classList.add('hidden');
        hamburger.classList.remove('active');
        menu.setAttribute('aria-hidden', true);
        saveMenuState(); // Save the menu state
    }

    window.hideMenu = hideMenu;


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
                const  templateTitle = response.headers.get('templateTitle');
                return response.text().then(html => ({ html, templateName, templateTitle, href }));
            })
            .then(({ html, templateName, templateTitle, href }) => {
                container.innerHTML = html;

                // Check if the current state is different from the previous one
                const currentState = window.history.state;
                const newState = { templateName, href, templateTitle };
                if (!currentState || currentState.templateName !== newState.templateName || currentState.href !== newState.href || currentState.templateTitle !== newState.templateTitle) {
                    window.history.pushState(newState, '', href);
                }

                loadTemplate(templateName, templateTitle);
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
        if (!href || href.startsWith('#') || href.startsWith('http')) return;

        const isMobileLink = link.getAttribute('data-mobile') === 'true';
        const isLoaderDisabled = link.getAttribute('data-loader') === 'false';

        if (isMobileLink) {
            hideMenu();
        }

        event.preventDefault();
        navigateTo(href, isLoaderDisabled);
    }

    function handlePopState(event) {
        if (event.state) {
            const { href, templateName, templateTitle } = event.state;
            if (href && templateName && templateTitle) {
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
                        loadTemplate(templateName, templateTitle);
                    })
                    .catch(error => {
                        console.error('Error loading page:', error);
                        showError('Errore di connessione. Assicurati di essere online e riprova.');
                    })
                    .finally(() => {
                        hideLoader();
                    });
            }
        } else {
            clearMenuState(); // Clear menu state when there's no navigation history
        }
    }

    restoreMenuState(); // Restore the menu state on page load
    document.body.addEventListener('click', navigate);
    window.addEventListener('popstate', handlePopState);
});
