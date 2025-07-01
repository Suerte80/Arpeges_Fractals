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

    button.addEventListener("click", () => {
        // Affiche le contenu de l'article dans la modal
        const modalTitle = modalContainer.querySelector(".modal-title");
        const modalContent = modalContainer.querySelector(".modal-content-text");

        if (modalTitle) modalTitle.textContent = articleData.title;
        if (modalContent) modalContent.textContent = articleData.content;

        modalContainer.classList.remove("hidden");
    });
}