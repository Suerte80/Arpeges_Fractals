document.addEventListener("DOMContentLoaded", () => {
    const fullArticlescontainer = document.getElementById("full-articles");
    const newArticlesContainer = document.querySelector("#latest-articles .slider-track");
    const mostViewedArticlesContainer = document.querySelector("#most-viewed-articles .articles");
    const modalContainer = document.getElementById("modal");
    const modalContainerStar = document.querySelector("#modal .favorite-star");

    // Elements exclusif a la page contact
    const modalHisto = document.getElementById("modal-histo");
    const buttonHisto = document.getElementById("button-histo");
    const buttonSend = document.getElementById("button-send");
    const buttonClear = document.getElementById("button-clear")

    // Fleche
    const arrow = document.getElementById("arrow");

    const newArticles = [];
    const mostViewedArticles = [];
    const fullListArticles = [];

    // On commente cette partie pour éviter de charger les articles et le faire dans le PHP. 
    handleLoadArticle(fullArticlescontainer,
        newArticlesContainer,
        mostViewedArticlesContainer,
        modalContainer
    );

    // Fermeture de la modal lors du click de la souris
    if (modalContainer) { // On vérifie qu'on a bien la modal de disponible
        document.getElementById("close-modal").addEventListener("click", () => {
            modalContainer.classList.add("hidden");
            handleScrollCapability();
        });
    }

    // ON attache l'event d'ajout de favoris au span étoile
    if (modalContainer) {
        const modFav = document.querySelector("#modal .favorite-star");
        modFav.addEventListener("click", () => {
            toggleFavorite(modFav, modalContainer.dataset.id);
        });
    }

    // Section pour le bouton historique de la page contact
    if (modalHisto && buttonHisto) {
        buttonHisto.addEventListener("click", () => {
            callBackHistoryOnClick(modalHisto);
        });

        buttonClear.addEventListener("click", () => {
            MessageManager.clearMessages();

            // Faire appel a la fonction pour rafraichir l'historique.
            callBackHistoryOnClick(modalHisto);
        });
    }

    // Section pour le bouton envoyer de la page contact
    handleFormSubmission(modalHisto, buttonSend);

    handleArrowScrollDown(arrow);

    generateParticles(
        document.getElementsByClassName("particles-container")[0]
    );

    generateParticles(
        document.getElementsByClassName("particles-container")[1]
    );

    handleMathGirlsAnimation();

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

            console.log(data);

            data.forEach(element => {
                createNotification(
                    element['message'],
                    2000,
                    element['type']
                );
            });

        });
});