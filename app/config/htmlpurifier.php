<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class HtmlPurifierConfig
{
    private HTMLPurifier $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();

        $config->set('HTML.Allowed', 'p,a[href|target],strong,em,ul,ol,li,h1,h2,h3,blockquote,code,pre,br,img[src|alt]');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');

        $config->set('HTML.SafeEmbed', true);
        $config->set('HTML.SafeObject', true); // ✅ Celle-ci est correcte

        $config->set('Attr.EnableID', true);
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        $config->set('AutoFormat.RemoveEmpty', true);

        $this->purifier = new HTMLPurifier($config);
    }

    public function purify($content)
    {
        return $this->purifier->purify($content);
    }
}