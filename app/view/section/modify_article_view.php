<?php

/**
 * @var $resArticle Array
 */
?>

<main class="article-container">
    <form action="/modify-article" method="POST" class="form-edit-article" enctype="multipart/form-data">
        <input type="hidden" name="article-id" value="<?= $resArticle['article-id'] ?>">

        <header class="article-header">
            <label for="article-title">
                <h2>Titre de l'article</h2>
            </label>
            <input type="text" id="article-title" name="title" class="article-input" value="<?= htmlspecialchars($resArticle['title']) ?>" required>

            <p class="article-meta">Publié le <time datetime="<?= $resArticle['parution_date'] ?>"><?= $resArticle['parution_date'] ?></time> par <strong><?= $resArticle['creator_firstname'] . ' ' . $resArticle['creator_lastname'] ?></strong></p>
        </header>

        <figure class="article-figure">
            <img src="<?= ImageManager::PUBLIC_STORAGE_ARTICLE . $resArticle['image_pres']['filepath'] ?>" alt="Illustration de l'article" class="article-img" />
            <input type="file" name="image" id="form-image" accept="image/*"">
        </figure>

        <section class=" article-content">
            <label for="article-description">
                <h3>Description</h3>
            </label>

            <textarea id="article-description" name="description" rows="5" class="article-textarea"><?= htmlspecialchars($resArticle['description']) ?></textarea>
            </section>

            <section class=" article-content">
                <label for="article-content">
                    <h3>Contenu</h3>
                </label>
                <!-- <textarea id="article-content" name="content" rows="20" class="article-textarea"><?= htmlspecialchars($resArticle['content']) ?></textarea> -->
                <div class="editorjs"></div>
            </section>

            <div class="article-actions">
                <button type="submit" class="button-glow">Enregistrer les modifications</button>
                <a href="/article?id=<?= $resArticle['article-id'] ?>" class="button-secondary">Annuler</a>
            </div>
    </form>
</main>