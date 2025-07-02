document.addEventListener("DOMContentLoaded", () => {
    const fullArticlescontainer = document.getElementById("full-articles");
    const newArticlesContainer = document.querySelector("#latest-articles .articles");
    const mostViewedArticlesContainer = document.querySelector("#most-viewed-articles .articles");
    const modalContainer = document.getElementById("modal");
    const modalContainerStar = document.querySelector("#modal .favorite-star");

    // Elements exclusif a la page contact
    const modalHisto = document.getElementById("modal-histo");
    const buttonHisto = document.getElementById("button-histo");
    const buttonSend = document.getElementById("button-send");

    const newArticles = [];
    const mostViewedArticles = [];
    const fullListArticles = [];

    // On vérifie que les éléments sont présent dans la page.
    if( fullArticlescontainer || newArticlesContainer || mostViewedArticlesContainer ){
        fetch("articles/articles.json")
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur lors du chargement des articles");
            }
            return response.json();
        }) .then(data => {

            data.forEach(article => {
                if( article.new ){
                    newArticles.push(article);
                } else if( article.mostViewed ){
                    mostViewedArticles.push(article);
                }
                
                const htmlArticle = addArticle(article, newArticlesContainer, mostViewedArticlesContainer, fullArticlescontainer);
                callbackModalManager(htmlArticle, modalContainer, article)
                fullListArticles.push(article, modalContainer);
            });

        }) .catch(error => {
            console.error("Erreur lors du chargement des articles :", error);
        });
    }
    
    // Fermeture de la modal lors du click de la souris
    if( modalContainer ){ // On vérifie qu'on a bien la modal de disponible
        document.getElementById("close-modal").addEventListener("click", () => {
            modalContainer.classList.add("hidden");
        });
    }

    // ON attache l'event d'ajout de favoris au span étoile
    if( modalContainer ){
        const modFav = document.querySelector("#modal .favorite-star");
        modFav.addEventListener("click", () => {
            toggleFavorite(modFav, modalContainer.dataset.id)
        });
    }

    // Section pour le bouton historique de la page contact
    if( modalHisto && buttonHisto ){
        buttonHisto.addEventListener("click", () => {
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
                    const li = document.createElement("li");

                    li.textContent = `<${message.email}>:${message.objet}:${message.message}`;

                    htmlUL.appendChild(li);           
                });
            } else{
                titleHistoModal.textContent = "Aucun Historique";
            }

            // On ouvre la modal
            modalHisto.classList.remove("hidden");
        });

        // On récupére le bouton
        const closeModalButton = modalHisto.querySelector("#close-modal");
        // On ajoute le callback sur le click du bouton
        closeModalButton.addEventListener("click", () => {
            modalHisto.classList.add("hidden");
        });
    }
    
    // Section pour le bouton envoyer de la page contact
    if( buttonSend ){
        const emailForm = document.getElementById("form-email");
        const objectForm = document.getElementById("form-objet");
        const messageForm = document.getElementById("form-message");

        buttonSend.addEventListener("click", (e) => {
            e.preventDefault();

            let isErrorOnForm = false;

            // Vérification des inputs
            const email = emailForm.value;
            if( email === "" && !validateEmail(email)){
                // Ici on trigger la notification
                toggleNotification("Email invalide", 2000, notificationMessageTypes.ERROR);
                toggleInvalidityForm(emailForm, 2000);
                isErrorOnForm = true;
            }

            const object = objectForm.value;
            if( object === "" ){
                // Ici on trigger la notif
                toggleNotification("Objet vide", 2000, notificationMessageTypes.ERROR);
                toggleInvalidityForm(objectForm, 2000);
                isErrorOnForm = true;
            }

            const message = messageForm.value;
            if( message === "" ){
                // Ici on trigger la notif.
                toggleNotification("Message vide", 2000, notificationMessageTypes.ERROR);
                toggleInvalidityForm(messageForm, 2000);
                isErrorOnForm = true;
            }

            if(isErrorOnForm) return;

            MessageManager.save(email, object, message);

            // Ici on trigger la notif
            toggleNotification("Message envoyé", 5000, notificationMessageTypes.INFO);
        })
    }
});