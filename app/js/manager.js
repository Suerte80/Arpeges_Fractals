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
    save(email, objet, message, date) {
        const objMessage = {
            id: this.lastMessageID() + 1,
            date: (date)?date.getTime():new Date(),
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
    },

    /**
     * Efface tous les messages dans le sessionStorage.
     */
    clearMessages(){
        sessionStorage.removeItem(this.messageKey);
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
                addClass(modalSpanStar, "active");
                modalSpanStar.innerHTML = "&#9829;"; // étoile pleine
            } else{
                removeClass(modalSpanStar, "active");
                modalSpanStar.innerHTML = "&#9825;"; // étoile vide
            }
        } else{
            // Aucun fav enregistré, on part sur étoile vide
            removeClass(modalSpanStar, "active");
            modalSpanStar.innerHTML = "&#9825;";
        }
        if (modalTitle) modalTitle.textContent = articleData.title;
        if (modalContent) modalContent.textContent = articleData.content;

        // On affiche la modal.
        removeClass(modalContainer, "hidden");
        handleScrollCapability();

        const focusable = modalContainer.querySelector("button, [tabindex='0'], a, input, textarea");
        if (focusable) {
            focusable.focus();
        }
    });

    modalContainer.addEventListener("keydown", (event) => {
        if( event.key === "Escape" ){
            addClass(modalContainer, "hidden");
            handleScrollCapability();
        }
    });
}

function callBackHistoryOnClick(modalHisto)
{
    if(!modalHisto)
        return;

    // On récupére l'historique
    const arrMessages = MessageManager.loadMessages();

    // On récupére le ul
    const htmlUL = modalHisto.querySelector("ul");
    
    // On récupére le titre de la modal
    const titleHistoModal = modalHisto.querySelector("h2");

    // ON néttoie le fils de htmlUL
    removeChildren(htmlUL);

    if( arrMessages.length > 0 ){
        titleHistoModal.textContent = "Historique";

        arrMessages.forEach(message => {
            createHistoListItem(htmlUL, message);       
        });
    } else{
        titleHistoModal.textContent = "Aucun Historique";
    }

    // On ouvre la modal
    removeClass(modalHisto, "hidden");

    // On récupére le bouton
    const closeModalButton = modalHisto.querySelector("#close-modal");
    // On ajoute le callback sur le click du bouton
    closeModalButton.addEventListener("click", () => {
        addClass(modalHisto, "hidden");
    });
}

function handleFormSubmission(modalHisto, buttonSend)
{
    if(!modalHisto && !buttonSend)
        return;

    if( buttonSend ){
        const emailForm = document.getElementById("form-email");
        const objectForm = document.getElementById("form-objet");
        const messageForm = document.getElementById("form-message");

        buttonSend.addEventListener("click", (e) => {
            e.preventDefault();

            let isErrorOnForm = false;

            // Vérification des inputs
            const email = emailForm.value;
            console.log(email, " : ", validateEmail(email));
            if( email === "" || !validateEmail(email)){
                // Ici on trigger la notification
                createNotification("Email invalide", 2000, notificationMessageTypes.ERROR);
                toggleInvalidityForm(emailForm, 2000);
                isErrorOnForm = true;
            }

            const object = objectForm.value;
            if( object === "" ){
                // Ici on trigger la notif
                createNotification("Objet vide", 2000, notificationMessageTypes.ERROR);
                toggleInvalidityForm(objectForm, 2000);
                isErrorOnForm = true;
            }

            const message = messageForm.value;
            if( message === "" ){
                // Ici on trigger la notif.
                createNotification("Message vide", 2000, notificationMessageTypes.ERROR);
                toggleInvalidityForm(messageForm, 2000);
                isErrorOnForm = true;
            }

            if(isErrorOnForm) return;

            MessageManager.save(email, object, message);

            // Ici on trigger la notif
            createNotification("Message envoyé", 5000, notificationMessageTypes.INFO);
        });
    }
}

function handleArrowScrollDown(arrow)
{
    if( arrow ){
        arrow.addEventListener("click", () => {
            const target = document.getElementById("latest-articles");
            if(target){
                target.scrollIntoView({behavior: "smooth"});
            }
        });
    }
}

function handleLoadArticle(
    fullArticlescontainer,
    newArticlesContainer,
    mostViewedArticlesContainer,
    modalContainer
)
{
    if(!modalContainer)
        return;

    // On vérifie que les éléments sont présent dans la page.
    if( fullArticlescontainer || newArticlesContainer || mostViewedArticlesContainer){
        fetch("data/articles.json")
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur lors du chargement des articles");
            }
            return response.json();
        }) .then(data => {

            data.forEach(article => {
                const htmlArticle = addArticle(article, newArticlesContainer, mostViewedArticlesContainer, fullArticlescontainer);
                callbackModalManager(htmlArticle, modalContainer, article);
            });

            // merci claude
            requestAnimationFrame(() => {
                requestAnimationFrame(() =>{
                    handleNewArticleSlider();
                });
            });

        }) .catch(error => {
            console.error("Erreur lors du chargement des articles :", error);
        });
    }
}