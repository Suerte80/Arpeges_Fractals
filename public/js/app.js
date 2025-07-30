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

    updateNotification();

    // TODO Refactorisé ce code
    // On récupère tous les boutons avec la classe .edit-btn
    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", e => {
            e.preventDefault();

            const target = button.dataset.target;
            const span = document.querySelector(`.field-value[data-field="${target}"]`);
            const input = document.querySelector(`input[name="${target}"]`);

            if (target === "image") {
                let input = document.createElement("input");
                input.type = 'file';
                input.accept = 'image/*';

                console.log(input);

                input.addEventListener("change", e => {
                    const file = e.target.files[0];
                    if (!file) {
                        alert("Aucun fichier séléctionner !");
                        return;
                    }

                    if (!file.type.startsWith("image/")) {
                        alert("Seuls les fichiers image sont autorisés.");
                        return;
                    }

                    const formData = new FormData();
                    formData.append('profile-image-update', 'true');
                    formData.append("image", file);

                    fetch('/api/upload', {
                        method: 'POST',
                        body: formData,
                    })
                        .then(res => res.json())
                        .then(data => {
                            console.log(data);


                            // On met à jour l'image de profile
                            if (data.success) {
                                // TODO Il faut que le serveur envoie la nom de fichier.
                                document.querySelector('#image-profile').src = data.filename;
                            }

                            updateNotification();
                        })
                        .catch(err => console.log(err));
                });

                input.click();
            } else {
                span.classList.add("hidden");
                input.classList.remove("hidden");
                input.focus();
            }
        });
    });

    const modifyArticleBtn = document.querySelector("#modify-article-btn");
    if (modifyArticleBtn) {
        modifyArticleBtn.addEventListener("click", e => {
            e.preventDefault();

            window.location = '/modify-article?id=' + modifyArticleBtn.dataset.id;

            console.log('Click');
        });
    }
});