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
