<!DOCTYPE html>
<html lang="fr">

<?php include('view/head.php'); ?>

<body>
    <?php include('view/navbar.php'); ?>

    <?php include('view/section/hero.php'); ?>

    <section id="latest-articles" aria-label="Articles récents">
        <h2>Derniers Articles</h2>
        <div class="articles-wrapper">
            <button type="button" class="slider-btn prev">&#10094;</button>
            <div class="articles slider-track">
                <!-- Tes .article ici -->
            </div>
            <button type="button" class="slider-btn next">&#10095;</button>
        </div>
    </section>

    <section id="most-viewed-articles" aria-label="Articles les plus vues">
        <h2>Les plus vus</h2>
        <div class="articles">
        </div>
    </section>

    <?php include('view/footer.php'); ?>

    <?php include('view/section/modal.php'); ?>

    <div id="notification" aria-live="polite"></div>

    <template id="template-new-article">
        <div class="article">
            <div class="upper-article">
                <span class="badge">Nouveau</span>
                <span class="favorite-star" title="Ajouter aux favoris">&#9829;</span>
            </div>
            <h3>Découverte et recos</h3>
            <img src="https://placehold.co/200x100" alt="Image représentant l'article">
            <p>Groupes émergents, albums de niche, scène locales : on creuse le son math rock.</p>
            <button class="button-glow" type="button">Lire</button>
        </div>
    </template>

</body>

</html>