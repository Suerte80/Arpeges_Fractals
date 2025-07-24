<?php
require_once __DIR__ . '/../controller/ArticleController.php';

$controller = new ArticleController();
$controller->getArticleJSON();