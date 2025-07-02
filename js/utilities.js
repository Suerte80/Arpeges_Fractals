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

function addArticle(article, newSection, mostViewedSection, fullSection, callBackButtonModal) {
  const templateArticle = document.getElementById("template-new-article");

  if (!templateArticle) {
    console.error("Impossible de charger les éléments du dom pour les articles.");
    return;
  }

  if (!article) {
    console.error("Impossible car la liste des articles est vide ou undefined !");
    return;
  }

  const favSaved = JSON.parse(localStorage.getItem("favorites") || "[]");
  const clonedArticle = templateArticle.content.cloneNode(true);

  clonedArticle.querySelector("h3").innerHTML = article.title;
  clonedArticle.querySelector("p").innerHTML = article.description;
  const favStar = clonedArticle.querySelector(".favorite-star");

  if (favSaved.includes(article.id)) {
    favStar.classList.add("active");
    favStar.innerHTML = "★"; // étoile pleine
  } else {
    favStar.classList.remove("active");
    favStar.innerHTML = "☆"; // étoile vide
  }

  const htmlCloneElement = clonedArticle.querySelector(".article");
  htmlCloneElement.dataset.id = article.id;

  if (!article.new) {
    clonedArticle.querySelector(".badge").style.display = "none";
  } else {
    if (newSection)
      newSection.appendChild(clonedArticle);
  }

  if (article.mostViewed) {
    if (mostViewedSection)
      mostViewedSection.appendChild(clonedArticle);
  }

  if (fullSection)
    fullSection.appendChild(clonedArticle);

  return htmlCloneElement;
}

function toggleStarInArticle(articleId, isActive)
{
  const htmlArticle = document.querySelector(`[data-id="${articleId}"] .favorite-star`);
  if(htmlArticle){
    if( isActive ){
      htmlArticle.classList.add("active");
      htmlArticle.innerHTML = "&#9733;"; // Étoile pleine
    } else{
      htmlArticle.classList.remove("active");
      htmlArticle.innerHTML = "&#9734;"; // Étoile vide
    }
  }
}

function toggleFavorite(starSpan, articleId) {
  const favorites = JSON.parse(localStorage.getItem("favorites") || "[]");

  const index = favorites.indexOf(articleId);
  if (index === -1) {
    favorites.push(articleId);
    starSpan.classList.add("active");
    starSpan.innerHTML = "&#9733;"; // Étoile pleine
    toggleStarInArticle(articleId, true);
  } else {
    favorites.splice(index, 1);
    starSpan.classList.remove("active");
    starSpan.innerHTML = "&#9734;"; // Étoile vide
    toggleStarInArticle(articleId, false);
  }

  localStorage.setItem("favorites", JSON.stringify(favorites));
}

/**
 * Supprime tous les enfants de element.
 * @param {HTMLElement} element Element parent ou il faut supprimer les enfants.
 */
function removeChildren(element) {
  if (!element)
    return;

  while(element.firstChild){
    element.removeChild(element.firstChild);
  }
}

const notificationMessageTypes = Object.freeze({
  INFO: "info",
  WARN: "warn",
  ERROR: "error"
});

function toggleNotification(message, duration, type ){
  const elementNotif = document.getElementById("notification");
  if(!elementNotif)
    return;

  // Affichage de la notif
  toggleNotifVisibility(elementNotif);

  elementNotif.classList.add(type);

  elementNotif.textContent = message;

  setTimeout(() => {
    toggleNotifVisibility(elementNotif);
  }, duration);
}

function toggleNotifVisibility(elementNotif)
{
  removeClassNotification(elementNotif);

  elementNotif.classList.toggle("visible");
}

function removeClassNotification(elementNotif)
{
  elementNotif.classList.remove("info");
  elementNotif.classList.remove("warn");
  elementNotif.classList.remove("error");
}

function toggleInvalidityForm(element, duration)
{
  if(!element)
    return;

  element.classList.toggle("forImnvalid");

  setTimeout(() => {
    element.classList.toggle("forImnvalid");
  }, duration);
}