<?php


namespace Templates\base;


use Lib\Base\BaseTemplate;

class IndexTemplate implements BaseTemplate
{
    public static function render($data)
    {
        $html = C(THEME_BASE_DIR . '/page/index.html', [
            'title' => ENV['title'],
            'subtitle' => ENV['subtitle'],
            'navbar' => C(THEME_BASE_DIR . '/components/navbar', [
                "title" => ENV['title'],
                "items" => implode("\n", array_map(function($v) {
                    return C(THEME_BASE_DIR . '/components/navbarItem', $v);
                }, THEME_CONFIG['navbar']))
            ]),
            'menu' => implode("\n", array_map(function($v) {
                return C(THEME_BASE_DIR . '/components/indexNav', $v);
            }, THEME_CONFIG['homepageMenu'])),
            'footer' => C(THEME_BASE_DIR . '/components/footer', [
                "busuanzi" => C(THEME_BASE_DIR . '/components/busuanzi')
            ])
        ]);

        file_put_contents(DIST_DIR . '/' . 'index.html', $html);
    }
}
