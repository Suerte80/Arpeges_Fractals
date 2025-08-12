<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/styles.css">

    <?php
    // On cherche le fichier JavaScript dans /asset/js
    $files = glob(__DIR__ . '/../../../public/assets/js/editor-js*.js');
    $editorJsBundle = !empty($files) ? '/assets/js/' . basename($files[0]) : '';
    ?>

    <?php
    // Quand je charge dynamiquement le script je n'ai aucun affichage de EditorJS
    ?>
    <!-- <script src="<?= $editorJsBundle ?>"></script> -->
    <script>
        window.editorJsBundle = '<?= $editorJsBundle ?>';
    </script>

    <script src="/js/utilities.js"></script>
    <script src="/js/manager.js"></script>
    <script src="/js/app.js"></script>
    <script src="/assets/js/imp.js"></script>

    <title>Arpèges Fractals</title>
</head>