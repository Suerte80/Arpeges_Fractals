<?php

/**
 * @var $resArticle Array
 */
?>

<main class="article-container">
    <article>
        <header class="article-header">
            <h2 class="article-title"><?= $resArticle['title'] ?></h2>
            <p class="article-meta">Publié le <time datetime="<?= $resArticle['parution_date'] ?>"><?= $resArticle['parution_date'] ?></time> par <strong class="article-author"><?= $resArticle['creator_firstname'] . ' ' . $resArticle['creator_lastname'] ?></strong></p>
            <?php if ($resArticle['user-can-edit']): ?>
                <button class="button-glow" id="modify-article-btn" data-id="<?= $resArticle['article-id'] ?>">Modifier</button>
            <?php endif; ?>
        </header>

        <figure class="article-figure">
            <img src="<?= ImageManager::PUBLIC_STORAGE_ARTICLE . $resArticle['image_pres']['filepath'] ?>" alt="Illustration de l'article" class="article-img" />
            <!--<figcaption class="article-caption">Figure 1 — L’univers visuel du math rock.</figcaption>-->
        </figure>

        <section id="article-content" class="article-content">
            <?= $resArticle['content'] ?>
        </section>
    </article>
</main>