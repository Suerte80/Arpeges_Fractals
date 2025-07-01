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


function addArticle(article, newSection, mostViewedSection, fullSection, callBackButtonModal)
{
    const templateArticle = document.getElementById("template-new-article");

    if( !templateArticle ){
        console.error("Impossible de charger les éléments du dom pour les articles.");
        return;
    }

    if( !article ){
        console.error("Impossible car la liste des articles est vide ou undefined !");
        return;
    }

    const clonedArticle = templateArticle.content.cloneNode(true);

    clonedArticle.querySelector("h3").innerHTML = article.title;
    clonedArticle.querySelector("p").innerHTML = article.description;

    const htmlCloneElement = clonedArticle.querySelector(".article");
    htmlCloneElement.dataset.id = article.id;

    if( !article.new ){
        clonedArticle.querySelector(".badge").style.display = "none";
    } else{
        if( newSection )
            newSection.appendChild(clonedArticle);
    }

    if( article.mostViewed ){
        if( mostViewedSection )
            mostViewedSection.appendChild(clonedArticle);
    }

    if(fullSection)
        fullSection.appendChild(clonedArticle);

    return htmlCloneElement;
}