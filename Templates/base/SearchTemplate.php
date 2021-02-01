<?php
namespace Templates\base;

use Lib\Base\BaseTemplate;

class SearchTemplate implements BaseTemplate
{
    public static function render($data)
    {
        if (!THEME_CONFIG['enableSearch']) return;
        $searchDistFilePath = DIST_DIR . '/search.html';
        if (!file_exists($searchDistFilePath)) {
            touch($searchDistFilePath);
        }
        $html = C(THEME_BASE_DIR . '/page/search.html', [
            'navbar' => C(THEME_BASE_DIR . '/components/navbar', [
                "title" => ENV['title'],
                "items" => implode("\n", array_map(function($v) {
                    return C(THEME_BASE_DIR . '/components/navbarItem', $v);
                }, THEME_CONFIG['navbar']))
            ]),
        ]);
        file_put_contents($searchDistFilePath, $html);
    }
}