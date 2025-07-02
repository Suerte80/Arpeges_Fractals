// Crée un élément HTML avec classe et contenu
function createElement(tag, className = "", content = "") {
    const el = document.createElement(tag);
    if (className) el.className = className;
    if (content) el.textContent = content;
    return el;
}

// Ajoute ou retire une classe
function toggleClass(el, className) {
    el.classList.toggle(className);
}

// Vérifie email (pour formulaire contact)
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
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
    const favStar = clonedArticle.querySelector(".favorite-star");

    // Vérification s'il est dans les articles favorie.
    if (favSaved.includes(article.id)) {
        favStar.classList.add("active");
        favStar.innerHTML = "★"; // étoile pleine
    } else {
        favStar.classList.remove("active");
        favStar.innerHTML = "☆"; // étoile vide
    }

    const htmlCloneElement = clonedArticle.querySelector(".article");
    htmlCloneElement.dataset.id = article.id;

    /*
     * Si un article est nouveau alors on affiche le badge sinon non.
     */
    if (!article.new) {
        clonedArticle.querySelector(".badge").style.display = "none";
    } else {
        if (newSection)
            newSection.appendChild(clonedArticle);
    }

    // Si un article fait partie des plus vues ou le met dans la section des plus vus.
    if (article.mostViewed) {
        if (mostViewedSection)
            mostViewedSection.appendChild(clonedArticle);
    }

    // Si la section ou il y a tous les articles est présente on le met dans la section.
    if (fullSection)
        fullSection.appendChild(clonedArticle);

    return htmlCloneElement;
}

/**
 * Permet de mettre a jour l'étoile qui représente si un article est en favoris ou non.
 * @param {Number} articleId Numéro identifiant de l'article.
 * @param {Boolean} isActive Représente si l'étoile doit être afficher pleine ou non.
 */
function toggleStarInArticle(articleId, isActive) {
    const htmlArticle = document.querySelector(`[data-id="${articleId}"] .favorite-star`);
    if (htmlArticle) {
        if (isActive) {
            htmlArticle.classList.add("active");
            htmlArticle.innerHTML = "&#9733;"; // Étoile pleine
        } else {
            htmlArticle.classList.remove("active");
            htmlArticle.innerHTML = "&#9734;"; // Étoile vide
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
        starSpan.classList.add("active");
        starSpan.innerHTML = "&#9733;"; // Étoile pleine
        toggleStarInArticle(articleId, true);
    } else {
        favorites.splice(index, 1);
        starSpan.classList.remove("active");
        starSpan.innerHTML = "&#9734;"; // Étoile vide
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
 * Afficher désaffiche la notification de l'écran.
 * @param {HTMLElement} elementNotif Element html de la notification.
 */
function toggleNotifVisibility(elementNotif) {
    removeClassNotification(elementNotif);
    elementNotif.classList.toggle("visible");
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

    element.classList.toggle("forImnvalid");

    setTimeout(() => {
        element.classList.toggle("forImnvalid");
    }, duration);
}

/**
 * Insére une balise <li> contenant un message.
 * @param {HTMLElement} htmlUl Représente une balise <ul> ou l'ont insere un message.
 * @param {Object} objMessage Message a inserer.
 */
function createHistoListItem(htmlUl, objMessage)
{
    const templateHistoryMessage = document.getElementById("template-message-history");

    if( !htmlUl || !objMessage )
        return;

    if( !templateHistoryMessage ){
        console.warn("Impossible de trouver la templateHistoryMessage");
        return;
    }

    // Clonage de la template
    const fragment = cloneTemplate(templateHistoryMessage);
    const htmlMessage = fragment.querySelector("li");

    // Récupération des différents éléments de la template
    const email = htmlMessage.querySelector(".history-email span");
    const object = htmlMessage.querySelector(".history-object span");
    const message = htmlMessage.querySelector(".history-message span");

    console.log(objMessage);

    // Population de la copie
    email.textContent = objMessage.email;
    object.textContent = objMessage.objet;
    message.textContent = objMessage.message;

    htmlUl.appendChild(htmlMessage);
}

function cloneTemplate(templateElement)
{
    if(!templateElement)
        return;

    return templateElement.content.cloneNode(true);
}