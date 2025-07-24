<!DOCTYPE html>
<html lang="fr">

<?php include('view/head.php'); ?>

<body>
    <?php include('view/navbar.php'); ?>

    <section id="contact">
        <div class="form-contact article">
            <h2>Contact</h2>
            <form action="get">
                <label for="form-email">email</label>
                <input type="email" name="email" id="form-email">

                <label for="form-objet">Objet</label>
                <input type="text" name="objet" id="form-objet">

                <label for="form-message">Message</label>
                <textarea name="Message" id="form-message" cols="30" rows="10"></textarea>

                <div id="form-buttons">
                    <button id="button-histo" class="button-glow" type="button">Historique</button>
                    <button id="button-send" class="button-glow" type="submit">Envoyer</button>
                </div>
            </form>
        </div>
    </section>

    <div id="modal-histo" class="modal hidden" role="dialog" aria-modal="true">
        <div class="modal-inner">
            <div>
                <button id="close-modal" class="close-button" type="button"
                    aria-label="Fermer la fenêtre">&times;</button>
                <!-- <button class="favorite-star" title="Ajouter aux favoris" type="button" aria-label="Ajouter aux favoris">&#9734;</button> -->
            </div>
            <h2 class="modal-title">Historique des messages</h2>
            <ul class="modal-content-text"></ul>

            <button id="button-clear" class="button-glow hidden" type="button">Effacer Historique</button>
        </div>
    </div>

    <div id="notification" aria-live="polite"></div>

    <?php include('view/footer.php'); ?>

    <template id="template-message-history">
        <li class="history-li">
            <div class="message-container">
                <p class="history-date"><strong>Date :</strong> <span></span></p>
                <p class="history-email"><strong>Email :</strong> <span></span></p>
                <p class="history-object"><strong>Objet :</strong> <span></span></p>
                <br>
                <p class="history-message"><strong>Message :</strong> <span></span></p>
            </div>
        </li>
    </template>
</body>

</html>