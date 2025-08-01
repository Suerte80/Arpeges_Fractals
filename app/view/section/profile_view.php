<?php

/**
 * @var Array $userInfo
 */
?>

<main id="profile" style="padding-top: 100px; color: var(--text); text-align: center;">
    <h2>Mon Profil</h2>

    <div class="math-girl-wrapper" style="margin: 2rem 0;">
        <img id="image-profile" src="<?= ImageManager::PUBLIC_STORAGE_AVATAR . $userInfo['image_filepath'] ?>" alt="<?= $userInfo['image_alt'] ?>" class="math-girl" style="max-width: 200px; border-radius: 50%;">
        <button class="edit-btn edit-img-btn" data-target="image">✎</button>
    </div>

    <div class="profile-info">
        <form action="/profile" method="post">
            <div class="profile-field">
                <label for="firstname">Prénom :</label>
                <span class="field-value" data-field="firstname"><?= $userInfo['firstname'] ?></span>
                <input type="text" name="firstname" class="field-input hidden" value="<?= $userInfo['firstname'] ?>">
                <button class="edit-btn" data-target="firstname">✎</button>
            </div>

            <div class="profile-field">
                <label for="lastname">Nom :</label>
                <span class="field-value" data-field="lastname"><?= $userInfo['lastname'] ?></span>
                <input type="text" name="lastname" class="field-input hidden" value="<?= $userInfo['lastname'] ?>">
                <button class="edit-btn" data-target="lastname">✎</button>
            </div>

            <div class="profile-field">
                <label for="username">Pseudo :</label>
                <span class="field-value" data-field="username"><?= $userInfo['username'] ?></span>
                <input type="text" name="username" class="field-input hidden" value="<?= $userInfo['username'] ?>">
                <button class="edit-btn" data-target="username">✎</button>
            </div>

            <div class="profile-field">
                <label for="email">Email :</label>
                <span class="field-value" data-field="email"><?= $userInfo['email'] ?></span>
                <input type="email" name="email" class="field-input hidden" value="<?= $userInfo['email'] ?>">
                <button class="edit-btn" data-target="email">✎</button>
            </div>

            <div class="profile-field centered">
                <input type="submit" value="Modifier son profile" name="submit-change-profile" id="submit-change-profile" class="button-glow">
            </div>
        </form>
    </div>

    <div style="margin-top: 2rem;">
        <a href="/logout" class="button-glow">Se déconnecter</a>
    </div>
</main>