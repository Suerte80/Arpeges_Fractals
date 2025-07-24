<!DOCTYPE html>
<html lang="fr">

<?php include('view/head.php'); ?>

<body>
    <?php include('view/navbar.php'); ?>

    <section id="all-articles" aria-label="Liste des articles">
        <h3>Liste des articles</h3>

        <div id="full-articles">
        </div>
    </section>

    <?php include('view/footer.php'); ?>

    <?php include('view/section/modal.php'); ?>

    <template id="template-new-article">
        <div class="article">
            <div class="upper-article">
                <span class="badge">Nouveau</span>
                <span class="favorite-star" title="Ajouter aux favoris">&#9734;</span>
            </div>
            <h3>Découverte et recos</h3>
            <img src="https://placehold.co/200x100" alt="Image représentant l'article">
            <p>Groupes émergents, albums de niche, scène locales : on creuse le son math rock.</p>
            <button class="button-glow" type="button">Lire</button>
        </div>
    </template>
</body>

</html>