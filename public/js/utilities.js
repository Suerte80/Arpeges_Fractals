// Crée un élément HTML avec classe et contenu
function createElement(tag, className = "", content = "") {
    const el = document.createElement(tag);
    if (className) el.className = className;
    if (content) el.textContent = content;
    return el;
}

// Ajoute ou retire une classe
function toggleClass(el, className, toggle = true, active = true) {
    if (!el)
        return;

    el.classList.toggle(className);
}

function addClass(el, className) {
    if (!el)
        return;

    el.classList.add(className);
}

function removeClass(el, className) {
    if (!el)
        return;

    el.classList.remove(className);
}

// Vérifie email (pour formulaire contact)
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric'
    });
}

function isRecentArticle(article) {
    if (!article || !article.parution_date) return false;

    const articleDate = new Date(article.parution_date);
    const now = new Date();
    const diffTime = Math.abs(now - articleDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    console.log(diffDays, " : ", article.parution_date);

    return diffDays <= 80; // Considérer un article comme récent s'il a été publié dans les 7 derniers jours
}

/**
 * Permet de créer un article dans la page html en fonction de s'ils sont des nouveaux, ... etc...
 * @param {Object} article Objet qui représente un article ( id, description, content, ... )
 * @param {HTMLElement} newSection Element html qui est la section des nouveaux articles. 
 * @param {HTMLElement} mostViewedSection Element html qui est la section des articles les plus vues.
 * @param {HTMLElement} fullSection Element html qui représente la section ou il y a tous les articles.
 * @param {Function} callBackButtonModal Callback sur le click du bouton close.
 * @returns null si une erreur est survenu
 * @returns L'élément html de l'article.
 */
function addArticle(article, newSection, mostViewedSection, fullSection, callBackButtonModal) {
    const templateArticle = document.getElementById("template-new-article");

    // Vérification des entrées.
    if (!templateArticle) {
        console.error("Impossible de charger les éléments du dom pour les articles.");
        return;
    }

    if (!article) {
        console.error("Impossible car la liste des articles est vide ou undefined !");
        return;
    }

    // Récupération des éléments favoris ( dans le localStorage )
    const favSaved = JSON.parse(localStorage.getItem("favorites") || "[]");
    const clonedArticle = templateArticle.content.cloneNode(true); // Duplication du contenu de la template.

    // Selection des élément interne à clonedArticle
    clonedArticle.querySelector("h3").innerHTML = article.title;
    clonedArticle.querySelector("p").innerHTML = article.description;
    clonedArticle.querySelector("img").src = article.image_filepath;
    const favStar = clonedArticle.querySelector(".favorite-star");

    // Vérification s'il est dans les articles favorie.
    if (favSaved.includes(article.id)) {
        favStar.classList.add("active");
        favStar.innerHTML = "&#9829;"; // étoile pleine
    } else {
        favStar.classList.remove("active");
        favStar.innerHTML = "&#9825;"; // étoile vide
    }

    // Ajout de l'ajout aux favoris lors du clique de la souris sur l'étoile.
    favStar.addEventListener("click", () => {
        toggleFavorite(favStar, article.id);
    });

    const htmlCloneElement = clonedArticle.querySelector(".article");
    htmlCloneElement.dataset.id = article.id;

    /*
     * Si un article est nouveau alors on affiche le badge sinon non.
     */
    if (!isRecentArticle(article)) {
        clonedArticle.querySelector(".badge").style.display = "none";
    } else {
        if (newSection)
            newSection.appendChild(clonedArticle);
    }

    // Si un article fait partie des plus vues ou le met dans la section des plus vus.
    if (article.views > 100) {
        if (mostViewedSection)
            mostViewedSection.appendChild(clonedArticle);
    }

    // Si la section ou il y a tous les articles est présente on le met dans la section.
    if (fullSection)
        fullSection.appendChild(clonedArticle);

    return htmlCloneElement;
}

/**
 * Permet de mettre a jour l'étoile qui représente si un article est en favoris ou non. Changement de dernière minute maintenant c'est un coeur pour l'UX.
 * @param {Number} articleId Numéro identifiant de l'article.
 * @param {Boolean} isActive Représente si l'étoile doit être afficher pleine ou non.
 */
function toggleStarInArticle(articleId, isActive) {
    const htmlArticle = document.querySelector(`[data-id="${articleId}"] .favorite-star`);
    if (htmlArticle) {
        if (isActive) {
            addClass(htmlArticle, "active");
            htmlArticle.innerHTML = "&#9829;"; // Étoile pleine
            createNotification("Ajoutez aux favoris !", 5000, notificationMessageTypes.INFO);
        } else {
            removeClass(htmlArticle, "active");
            htmlArticle.innerHTML = "&#9825;"; // Étoile vide
            createNotification("Supprimer des favoris !", 5000, notificationMessageTypes.INFO);
        }
    }
}

/**
 * Affiche ou non l'étoile des favoris si il est présente dans le localStorage.
 * @param {HTMLElement} starSpan Est l'élément span ou il y a l'étoile.
 * @param {Number} articleId Numéro identifiant l'article.
 */
function toggleFavorite(starSpan, articleId) {
    const favorites = JSON.parse(localStorage.getItem("favorites") || "[]");

    const index = favorites.indexOf(articleId);
    if (index === -1) {
        favorites.push(articleId);
        addClass(starSpan, "active");
        starSpan.innerHTML = "&#9829;"; // Étoile pleine
        toggleStarInArticle(articleId, true);
    } else {
        favorites.splice(index, 1);
        removeClass(starSpan, "active");
        starSpan.innerHTML = "&#9825;"; // Étoile vide
        toggleStarInArticle(articleId, false);
    }

    localStorage.setItem("favorites", JSON.stringify(favorites));
}

/**
 * Supprime tous les enfants de element.
 * @param {HTMLElement} element Element parent ou il faut supprimer les enfants.
 */
function removeChildren(element) {
    if (!element)
        return;

    while (element.firstChild) {
        element.removeChild(element.firstChild);
    }
}

/**
 * Représente les états possible de la notification.
 */
const notificationMessageTypes = Object.freeze({
    INFO: "info",
    WARN: "warn",
    ERROR: "error"
});

/**
 * Affiche la notification avec un etat type pendant une durée duration avec un message.
 * @param {String} message Message a afficher dans la notification.
 * @param {Number} duration Durée de l'affichage de la notification.
 * @param {notificationMessageTypes} type Etat de la notification.
 */
function createNotification(message, duration, type) {
    const elementNotif = document.getElementById("notification");
    if (!elementNotif)
        return;

    // Affichage de la notif
    toggleNotifVisibility(elementNotif);

    elementNotif.classList.add(type);
    elementNotif.textContent = message;

    setTimeout(() => {
        toggleNotifVisibility(elementNotif);
    }, duration);
}

/**
 * Met a jours les notifications qui sotn stocké sur le serveur distant /api/notifications
 */
function updateNotification() {
    // On fetch les notifications depuis /api/notifications
    fetch('/api/notifications')
        .then(response => response.json())
        .then(data => {
            // Exemples de données
            /*
            [
                {"type": "info", "message": "message_texte"},
                ...
            ]
             */

            if (Array.isArray(data)) {
                data.forEach(element => {
                    createNotification(
                        element['message'],
                        2000,
                        element['type']
                    );
                });
            }

        });
}

/**
 * Afficher désaffiche la notification de l'écran.
 * @param {HTMLElement} elementNotif Element html de la notification.
 */
function toggleNotifVisibility(elementNotif) {
    removeClassNotification(elementNotif);
    toggleClass(elementNotif, "visible");
}

/**
 * Enlève les états de la notifications.
 * @param {HTMLElement} elementNotif Element html de la notification.
 */
function removeClassNotification(elementNotif) {
    elementNotif.classList.remove("info");
    elementNotif.classList.remove("warn");
    elementNotif.classList.remove("error");
}

/**
 * Affiche les bordures en rouges pendant une certaine durée.
 * @param {HTMLFormElement} element Element html
 * @param {Number} duration Durée de l'affichage.
 */
function toggleInvalidityForm(element, duration) {
    if (!element)
        return;

    toggleClass(element, "formInvalid");

    setTimeout(() => {
        toggleClass(element, "formInvalid");
    }, duration);
}

/**
 * Insére une balise <li> contenant un message.
 * @param {HTMLElement} htmlUl Représente une balise <ul> ou l'ont insere un message.
 * @param {Object} objMessage Message a inserer.
 */
function createHistoListItem(htmlUl, objMessage) {
    const templateHistoryMessage = document.getElementById("template-message-history");

    if (!htmlUl || !objMessage)
        return;

    if (!templateHistoryMessage) {
        console.warn("Impossible de trouver la templateHistoryMessage");
        return;
    }

    // Clonage de la template
    const fragment = cloneTemplate(templateHistoryMessage);
    const htmlMessage = fragment.querySelector("li");

    // Récupération des différents éléments de la template
    const date = htmlMessage.querySelector(".history-date span");
    const email = htmlMessage.querySelector(".history-email span");
    const object = htmlMessage.querySelector(".history-object span");
    const message = htmlMessage.querySelector(".history-message span");

    console.log(date);

    // Population de la copie
    date.textContent = formatDate(new Date(objMessage.date));
    email.textContent = objMessage.email;
    object.textContent = objMessage.objet;
    message.textContent = objMessage.message;

    htmlUl.appendChild(htmlMessage);
}

/**
 * Clone un élément template et retourne le contenu de la copie.
 * @param {HTMLElement} templateElement Element template a cloner.
 * @returns Un HTMLFragment qui est le clone.
 */
function cloneTemplate(templateElement) {
    if (!templateElement)
        return;

    return templateElement.content.cloneNode(true);
}

/**
 * Affiche des particules dans le conteneur.
 * @param {HTMLElement} container Conteneur pour les particules.
 * @param {Number} count Nombres de particules a afficher.
 */
function generateParticles(container, count = 20) {
    if (!container)
        return;

    for (let i = 0; i < count; i++) {
        const p = document.createElement("div");
        p.className = "particle";

        // Position aléatoire autour de l'image
        p.style.top = `${Math.random() * 100}%`;
        p.style.left = `${Math.random() * 100}%`;

        // Couleur math-rock aléatoire
        const colors = ["#FF6B6B", "#FFD93D", "#6BCB77", "#4D96FF", "#9D4EDD", "#F67280", "#F8B400", "#00C9A7"];
        p.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];

        // Délai aléatoire
        p.style.animationDelay = `${Math.random() * 4}s`;

        container.appendChild(p);
    }
}

/**
 * Gère le mouvement de la math-girl par rapport a la position de la souris.
 */
function handleMathGirlsAnimation() {
    const girls = document.querySelectorAll(".math-girl");
    if (girls.length === 0) return;

    // Throttling plus agressif (30fps au lieu de 60fps)
    let lastUpdate = 0;
    const throttleMs = 33; // ~30fps

    document.addEventListener("mousemove", (e) => {
        const now = Date.now();

        // Throttling temporel
        if (now - lastUpdate < throttleMs) return;
        lastUpdate = now;

        const { innerWidth, innerHeight } = window;
        const x = (e.clientX / innerWidth - 0.5) * 2;
        const y = (e.clientY / innerHeight - 0.5) * 2;

        // On multiplie par un coéf ( 20 ici )
        const offsetX = x * 20; // en px
        const offsetY = y * 20; // en px

        girls.forEach(girl => {
            const transform = girl.classList.contains("rotate90")
                ? `translate(${offsetX}px, ${offsetY}px) scaleX(-1)`
                : `translate(${offsetX}px, ${offsetY}px)`;

            girl.style.transform = transform;
        });
    });
}

/**
 * Détermine le nombre d'articles visibles selon la taille d'écran
 */
function getVisibleArticlesCount() {
    const screenWidth = window.innerWidth;
    if (screenWidth <= 768) return 1;
    if (screenWidth <= 1024) return 2;
    return 3;
}

/**
 * Calcule la largeur d'un article pour le slider
 */
function calculateArticleWidth(sliderTrack) {
    const visibleCount = getVisibleArticlesCount();
    const container = sliderTrack.parentElement;
    const containerStyle = window.getComputedStyle(container);

    const paddingLeft = parseFloat(containerStyle.paddingLeft) || 0;
    const paddingRight = parseFloat(containerStyle.paddingRight) || 0;
    const availableWidth = container.offsetWidth - paddingLeft - paddingRight;

    const gap = parseFloat(window.getComputedStyle(sliderTrack).gap) || 16;
    const totalGapWidth = gap * (visibleCount - 1);

    return (availableWidth - totalGapWidth) / visibleCount;
}

/**
 * Met à jour la position et l'état du slider
 */
function updateSliderPosition(sliderTrack, articles, index, prevBtn, nextBtn) {
    const visibleCount = getVisibleArticlesCount();
    const articleWidth = calculateArticleWidth(sliderTrack);
    const gap = parseFloat(window.getComputedStyle(sliderTrack).gap) || 16;

    if (articleWidth <= 0) return;

    // Mettre à jour les tailles des articles
    articles.forEach(article => {
        article.style.flex = `0 0 ${articleWidth}px`;
        article.style.width = `${articleWidth}px`;
        article.style.minWidth = `${articleWidth}px`;
        article.style.maxWidth = `${articleWidth}px`;
    });

    // Calculer la translation
    const stepWidth = articleWidth + gap;
    const translateX = -index * stepWidth;
    sliderTrack.style.transform = `translateX(${translateX}px)`;

    // Gérer l'état des boutons
    const maxIndex = Math.max(0, articles.length - visibleCount);
    const isAtStart = index <= 0;
    const isAtEnd = index >= maxIndex;

    prevBtn.disabled = isAtStart;
    nextBtn.disabled = isAtEnd;
    prevBtn.classList.toggle('disabled', isAtStart);
    nextBtn.classList.toggle('disabled', isAtEnd);
}

/**
 * Initialise les event listeners du slider
 */
function setupSliderControls(sliderTrack, articles, prevBtn, nextBtn) {
    let index = 0;

    const updateSlider = () => updateSliderPosition(sliderTrack, articles, index, prevBtn, nextBtn);

    // Navigation suivant
    const handleNext = () => {
        const maxIndex = Math.max(0, articles.length - getVisibleArticlesCount());
        if (index < maxIndex) {
            index++;
            updateSlider();
        }
    };

    // Navigation précédent
    const handlePrev = () => {
        if (index > 0) {
            index--;
            updateSlider();
        }
    };

    // Supprimer les anciens listeners et en créer de nouveaux
    const newPrevBtn = prevBtn.cloneNode(true);
    const newNextBtn = nextBtn.cloneNode(true);
    prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
    nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);

    newPrevBtn.addEventListener("click", handlePrev);
    newNextBtn.addEventListener("click", handleNext);

    // Redimensionnement avec debounce
    let resizeTimeout;
    const handleResize = () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const maxIndex = Math.max(0, articles.length - getVisibleArticlesCount());
            if (index > maxIndex) index = maxIndex;
            updateSlider();
        }, 250);
    };

    window.addEventListener('resize', handleResize);

    document.addEventListener("keydown", (e) => {
        if (e.key === "ArrowRight")
            handleNext();
        else if (e.key === "ArrowLeft")
            handlePrev();
    });

    // Initialisation
    updateSlider();
}

/**
 * Attend que les articles soient chargés et initialise le slider
 */
function waitForArticlesAndInitialize(sliderTrack, prevBtn, nextBtn) {
    const articles = sliderTrack.querySelectorAll(".article");

    if (articles.length === 0) {
        setTimeout(() => waitForArticlesAndInitialize(sliderTrack, prevBtn, nextBtn), 50);
        return;
    }

    setupSliderControls(sliderTrack, articles, prevBtn, nextBtn);
}

/**
 * Gère le comportement du slider - Version refactorisée
 */
function handleNewArticleSlider() {
    const sliderTrack = document.querySelector("#latest-articles .slider-track");
    const prevBtn = document.querySelector("#latest-articles .prev");
    const nextBtn = document.querySelector("#latest-articles .next");

    if (!sliderTrack || !prevBtn || !nextBtn) {
        console.warn("Éléments du slider non trouvés");
        return;
    }

    waitForArticlesAndInitialize(sliderTrack, prevBtn, nextBtn);
}

/**
 * Permet d'activer ou de désactivé le scroll
 */
let scrollPosition = 0;

function handleScrollCapability(toggleMode = true, activate = true) {
    const html = document.documentElement;
    const body = document.body;

    if (toggleMode) {
        const isDisabled = html.classList.contains('no-scroll');
        if (!isDisabled) {
            scrollPosition = window.scrollY || window.pageYOffset;
            addClass(html, "no-scroll");
            addClass(body, "no-scroll");
            body.style.top = `-${scrollPosition}px`;
        } else {
            removeClass(html, "no-scroll");
            removeClass(body, "no-scroll");
            body.style.top = '';
            window.scrollTo(0, scrollPosition);
        }
    } else {
        if (!activate) {
            scrollPosition = window.scrollY || window.pageYOffset;
            addClass(html, "no-scroll");
            addClass(body, "no-scroll");
            body.style.top = `-${scrollPosition}px`;
        } else {
            removeClass(html, "no-scroll");
            removeClass(body, "no-scroll");
            body.style.top = '';
            window.scrollTo(0, scrollPosition);
        }
    }
}