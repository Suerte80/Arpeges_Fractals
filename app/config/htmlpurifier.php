<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Allowed', 'p,a[href],strong,em,ul,ol,li,h1,h2,h3,blockquote,code,pre,br,img[src|alt]');
$config->set('HTML.Doctype', 'HTML 4.01 Transitional');
$config->set('URI.DisableJavaScript', true);
$config->set('AutoFormat.RemoveEmpty', true);

return new HTMLPurifier($config);