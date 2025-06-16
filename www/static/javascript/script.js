// Open and close menu when clicking the hamburger-button button
document.addEventListener("DOMContentLoaded", function () {
	//----------------------------------------------------
	//						Menu button
	//----------------------------------------------------
	const menuBtn = document.getElementById("hamburger-button");
	const menu = document.getElementById("menu");

	// Add focusout handler for the menu container
	menu.addEventListener("focusout", (event) => {
		// Check if the new focus target is outside menu and button
		const isClickInside =
			menu.contains(event.relatedTarget) ||
			menuBtn.contains(event.relatedTarget);
		if (!isClickInside) {
			onClickMenuBtn(menuBtn);
		}
	});

	const animalItems = document.getElementsByClassName("animal-item");
	if (animalItems) {
		for (let i = 0; i < animalItems.length; i++) {
			changeBackgroundImage(animalItems[i]);
		}
	}

	//----------------------------------------------------
	//				Service change in booking
	//----------------------------------------------------	
	//ToDO: read max people from db
	const maxPeopleMap = {
		"Safari": 10,
		"Ingresso": 100,
		"Visita guidata": 15
	};

	// Elementi HTML
	const serviceSelect = document.getElementById('service');
	const numberOfPeopleInput = document.getElementById('numberOfPeople');

	// Verifica che gli elementi esistano prima di aggiungere l'evento
	if (serviceSelect && numberOfPeopleInput) {
	  serviceSelect.addEventListener('change', () => updateMaxPeople(serviceSelect, numberOfPeopleInput, maxPeopleMap));
	}


	// Existing click handler
	menuBtn.addEventListener("click", () => onClickMenuBtn(menuBtn));
});

function onClickMenuBtn(button) {
	const menu = document.getElementById("menu");
	// const breadcrumb = document.getElementById('breadcrumb');

	if (menu.classList.contains("show-drop-content")) {
		menu.classList.remove("show-drop-content");

		// breadcrumb.classList.remove('show-drop-content');
		button.classList.remove("close-btn");
		button.setAttribute("aria-label", "Apri menù");
	} else {
		menu.classList.add("show-drop-content");

		// breadcrumb.classList.add('show-drop-content');
		button.classList.add("close-btn");
		button.setAttribute("aria-label", "Chiudi menù");
	}
}

function changeBackgroundImage(element) {
	const imageUrl = element.getAttribute("data-image-url");
	if (imageUrl) {
		element.style.backgroundImage = `url(${imageUrl})`;
	} else {
		console.warn("No background image URL found for element:", element);
	}
}

function updateMaxPeople(serviceSelect, numberOfPeopleInput, maxPeopleMap) {
		const selectedService = serviceSelect.value;
		const maxPeople = maxPeopleMap[selectedService] || 7; // Default max se non selezionato
		numberOfPeopleInput.max = maxPeople;
		numberOfPeopleInput.value = Math.min(numberOfPeopleInput.value, maxPeople); // Adatta il valore corrente
	}

//----------------------------------------------------
//					Form validation
//----------------------------------------------------	
document.addEventListener('DOMContentLoaded', function() {
    
    const serverErrorSummary = document.getElementById('error-summary-container');
    if (serverErrorSummary && serverErrorSummary.innerHTML.trim().includes('<li>')) {
        serverErrorSummary.removeAttribute('hidden');
        serverErrorSummary.focus();
    }
    
    const form = document.querySelector('form[novalidate]');
    if (!form) return;

    const errorSummaryContainer = document.getElementById('error-summary-container');
    const errorSummaryList = document.getElementById('error-summary-list');
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB
    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    form.addEventListener('submit', function(event) {
        clearAllErrors();
        const errors = validateForm();
        if (errors.length > 0) {
            event.preventDefault();
            showAllErrors(errors);
        }
    });

    /**
     * Esegue la validazione su tutti i campi del form.
     * @returns {Array} Un array di oggetti errore. Ogni oggetto ha {id, message}.
     */
    function validateForm() {
        const errors = [];
        const addError = (id, message) => errors.push({ id, message });

        if (form.action.includes('Animal.php')) {
            const textFields = ['name', 'species', 'habitat', 'dimensions', 'lifespan', 'diet', 'description'];
            textFields.forEach(id => {
                const input = document.getElementById(id);
                if (input.value.trim() === '') {
                    const label = document.querySelector(`label[for="${id}"]`).textContent;
                    addError(id, `Il campo "${label}" è obbligatorio.`);
                }
            });

            const ageInput = document.getElementById('age');
            const ageValue = ageInput.value.trim();
            if (ageValue === '') {
                addError('age', 'L\'età è obbligatoria.');
            } else if (!/^\d+$/.test(ageValue) || parseInt(ageValue, 10) < 0) {
                addError('age', 'L\'età deve essere un numero intero non negativo.');
            }

            const imageInput = document.getElementById('image');
            
            if (imageInput.hasAttribute('required') && imageInput.files.length === 0) {
                addError('image', 'L\'immagine è obbligatoria.');
            } 
            
            if (imageInput.files.length > 0) {
                const file = imageInput.files[0];
                if (file.size > MAX_FILE_SIZE) {
                    addError('image', 'Il file è troppo grande (massimo 5MB).');
                }
                if (!ALLOWED_TYPES.includes(file.type)) {
                    addError('image', 'Formato file non supportato (solo JPEG, PNG, WEBP).');
                }
            }
		} else if (form.action.includes('Booking.php')) {
			const serviceInput = document.getElementById('service');
            if (serviceInput && serviceInput.value === '') {
                addError('service', 'È obbligatorio selezionare un servizio.');
            }
			
			const peopleInput = document.getElementById('numberOfPeople');
			if (peopleInput.value.trim() === '') {
			    addError('numberOfPeople', 'Il numero di partecipanti è obbligatorio.');
			} else {
                const numPeople = parseInt(peopleInput.value, 10);
                if (numPeople < 1) {
                    addError('numberOfPeople', 'Il numero di partecipanti deve essere almeno 1.');
                }
                const maxPeople = peopleInput.getAttribute('max');
                if (maxPeople && numPeople > parseInt(maxPeople, 10)) {
                    addError('numberOfPeople', `Il numero di partecipanti non può superare ${maxPeople}.`);
                }
            }

            const dateInput = document.getElementById('date');
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const selectedDate = new Date(dateInput.value);
            
            if (dateInput.value.trim() === '') {
                addError('date', 'La data è obbligatoria.');
            } else if (selectedDate < today) {
                addError('date', 'La data non può essere nel passato.');
            }
        }
        return errors;
    }

    /**
     * Mostra tutti gli errori sul form.
     * @param {Array} errors - L'array di errori generato da validateForm.
     */
    function showAllErrors(errors) {
        errorSummaryList.innerHTML = '';
        errors.forEach(error => {
            const errorElement = document.getElementById(`${error.id}-error`);
            const inputElement = document.getElementById(error.id);
            
            if (errorElement && inputElement) {
                errorElement.textContent = error.message;
                errorElement.removeAttribute('hidden');
                inputElement.setAttribute('aria-invalid', 'true');
            }

            const listItem = document.createElement('li');
            const link = document.createElement('a');
            link.href = `#${error.id}`;
            link.textContent = error.message;
            listItem.appendChild(link);
            errorSummaryList.appendChild(listItem);
        });

        errorSummaryContainer.removeAttribute('hidden');
        errorSummaryContainer.focus();
    }

	function clearAllErrors() {
        errorSummaryContainer.setAttribute('hidden', 'true');
        errorSummaryList.innerHTML = '';

        const errorMessages = form.querySelectorAll('.error-message');
        errorMessages.forEach(msg => {
            msg.textContent = '';
            msg.setAttribute('hidden', 'true');
        });

        const invalidInputs = form.querySelectorAll('[aria-invalid="true"]');
        invalidInputs.forEach(input => input.removeAttribute('aria-invalid'));
    }
});