document.addEventListener("DOMContentLoaded", () => {
    const fullArticlescontainer = document.getElementById("full-articles");
    const newArticlesContainer = document.querySelector("#latest-articles .articles");
    const mostViewedArticlesContainer = document.querySelector("#most-viewed-articles .articles");
    const modalContainer = document.getElementById("modal");


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
        });
    }

});