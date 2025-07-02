document.addEventListener("DOMContentLoaded", () => {
    const fullArticlescontainer = document.getElementById("full-articles");
    const newArticlesContainer = document.querySelector("#latest-articles .articles");
    const mostViewedArticlesContainer = document.querySelector("#most-viewed-articles .articles");
    const modalContainer = document.getElementById("modal");
    const modalContainerStar = document.querySelector("#modal .favorite-star");


    console.log(newArticlesContainer);

    const newArticles = [];
    const mostViewedArticles = [];
    const fullListArticles = [];

    fetch("articles/articles.json")
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur lors du chargement des articles");
            }
            return response.json();
        }) .then(data => {
            console.log(data);

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
    
    // Fermeture de la modal lors du click de la souris
    if( modalContainer ){ // On vérifie qu'on a bien la modal de disponible
        document.getElementById("close-modal").addEventListener("click", () => {
            modalContainer.classList.add("hidden");
            modalContainer.querySelector("button, [tabindex='0']").focus();
        });
    }

    // ON attache l'event d'ajout de favoris au span étoile
    if( modalContainer ){
        const modFav = document.querySelector("#modal .favorite-star");
        modFav.addEventListener("click", () => {
            toggleFavorite(modFav, modalContainer.dataset.id)
        });
    }


});