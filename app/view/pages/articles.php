<!DOCTYPE html>
<html lang="fr">

<?php include(__DIR__ . '/../layout/head_view.php'); ?>

<body>
    <?php 
    include(__DIR__ . '/../layout/navbar_view.php');

    include(__DIR__ . '/../section/all_articles_view.php');
    include(__DIR__ . '/../layout/footer_view.php');
    include(__DIR__ . '/../section/notification_view.php');
    include(__DIR__ . '/../section/modal_view.php');
    include(__DIR__ . '/../template/new_article_view.php');
    ?>

</body>

</html>