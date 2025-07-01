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
