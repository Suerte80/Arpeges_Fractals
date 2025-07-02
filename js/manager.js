const ModalManager = {
    open(modalId) {
        document.getElementById(modalId)?.classList.add("visible");
    },
    close(modalId) {
        document.getElementById(modalId)?.classList.remove("visible");
    }
};

const StorageManager = {
    save(key, data) {
        localStorage.setItem(key, JSON.stringify(data));
    },
    load(key) {
        const data = localStorage.getItem(key);
        return data ? JSON.parse(data) : null;
    }
};

function callbackModalManager(article, modalContainer, articleData) {
    if (!article || !modalContainer) return;

    const button = article.querySelector("button");
    console.log(article);
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

        console.log("Article ID: ");
        console.log(articleId);

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
    });
}