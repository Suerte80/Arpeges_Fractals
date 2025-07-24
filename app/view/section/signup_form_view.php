<section id="contact">
    <div class="form-contact article">
        <h2>S'incrire</h2>
        <form action="/signup" method="post">

            <label for="firstname">Prénom</label>
            <input type="text" name="firstname" id="firstname" required>

            <label for="lastname">Nom</label>
            <input type="text" name="lastname" id="lastname" required>

            <label for="username">Pseudo</label>
            <input type="text" name="username" id="username" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="confirm-email">Confirmer votre email</label>
            <input type="email" name="confirm-email" id="confirm-email" required>

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm-password">Confirmer le mot de passe</label>
            <input type="password" name="confirm-password" id="confirm-password" required>

            <div id="form-buttons">
                <button id="button-signup-form" class="button-glow" type="submit">S'inscrire</button>
            </div>
        </form>
    </div>
</section>