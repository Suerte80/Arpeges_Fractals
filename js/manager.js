/**
 * Objet qui permet de gerer les sessionStorage.
 */
const SessionManager = {
    /**
     * @param {String} key La clé a inserer dans le sessionStorage. 
     * @param {String} data Les données a inserer. 
     */
    save(key, data) {
        sessionStorage.setItem(key, JSON.stringify(data));
    },
    /**
     * @param {String} key La clé a récupérer dans le sessionStorage.
     * @returns Les données récupérer dans le sessionStorage ou null si il n'y a rien.
     */
    load(key) {
        const data = sessionStorage.getItem(key);
        return data ? JSON.parse(data) : null;
    }
}

const MessageManager = {
    messageKey: "messages",

    /**
     * Sauvegarde un message dans le session storage.
     * @param {String} email Email de l'expéditeur.
     * @param {String} objet Object du message.
     * @param {String} message Message de l'expediteur.
     */
    save(email, objet, message) {
        const objMessage = {
            id: this.lastMessageID() + 1,
            email: email,
            objet: objet,
            message: message
        };

        let arrMessages = this.loadMessages();
        if( Array.isArray(arrMessages) ){
            arrMessages.push(objMessage);
        } else if( !arrMessages ){
            arrMessages = [objMessage];
        }

        sessionStorage.setItem(this.messageKey, JSON.stringify(arrMessages));
    },

    /**
     * Retourne l'id du dernier message enregistré.
     * @returns L'id du dernier message enregistré.
     */
    lastMessageID(){
        const data = this.loadMessages();
        if(!data || data.length === 0) return 0;
        const last = data[data.length-1];
        return last.id || 0;
    },

    /**
     * Retourne l'objet message qui correspond a l'ID.
     * @param {Number} id Id du message rechercher.
     * @returns L'objet message qui correspond a l'ID.
     */
    loadMessage(id){
        const data = this.loadMessages();
        if(!data) return null;
        return data.find(message => message.id == id);
    },

    /**
     * @returns Un array qui contient tous les objets messages ou un array vide s'il n'y pas d'objet message dans le sessions storage.
     */
    loadMessages(){
        const data = sessionStorage.getItem(this.messageKey);
        return data ? JSON.parse(data) : [];
    }
}

/**
 * Fonction qui permet de gérer l'ouverture et la fermeture de la modal.
 * @param {Object} article Objet article qui représente un article (titre, description, ....)
 * @param {HTMLElement} modalContainer Le HTMLElement qui représente le modal.
 * @param {Object} articleData Objet article qui représente un article (id, nom, ...).
 * @returns undefined si erreur.
 */
function callbackModalManager(article, modalContainer, articleData) {
    if (!article || !modalContainer) return;

    const button = article.querySelector("button");
    if (!button) {
        console.warn("Pas de bouton dans :", article);
        return;
    }

    button.addEventListener("click", function() {
        // Affiche le contenu de l'article dans la modal
        const modalTitle = modalContainer.querySelector(".modal-title");
        const modalContent = modalContainer.querySelector(".modal-content-text");

        // récupération dans le localstorage des favoris de l'utilisateur.
        const favSaved = JSON.parse(localStorage.getItem("favorites") || "[]");
        const modalSpanStar = document.querySelector("#modal .favorite-star");
        const articleId = this.parentElement.dataset.id;
        modalContainer.dataset.id = articleId;

        // Modification des éléments de la modal.
        if( favSaved && Array.isArray(favSaved) && favSaved.length > 0 ){
            if( favSaved.includes(articleId) ){
                modalSpanStar.classList.add("active");
                modalSpanStar.innerHTML = "★"; // étoile pleine
            } else{
                modalSpanStar.classList.remove("active");
                modalSpanStar.innerHTML = "☆"; // étoile vide
            }
        } else{
            // Aucun fav enregistré, on part sur étoile vide
            modalSpanStar.classList.remove("active");
            modalSpanStar.innerHTML = "☆";
        }
        if (modalTitle) modalTitle.textContent = articleData.title;
        if (modalContent) modalContent.textContent = articleData.content;

        // On affiche la modal.
        modalContainer.classList.remove("hidden");

        const focusable = modalContainer.querySelector("button, [tabindex='0'], a, input, textarea");
        if (focusable) {
            focusable.focus();
        }
    });

    modalContainer.addEventListener("keydown", (event) => {
        if( event.key === "Escape" )
            modalContainer.classList.add("hidden");
    });
}