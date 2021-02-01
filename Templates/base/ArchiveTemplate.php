<?php


namespace Templates\base;


use Lib\Base\BaseTemplate;

class ArchiveTemplate implements BaseTemplate
{
    public static function render($data)
    {
        $html = C(THEME_BASE_DIR . '/page/archive.html', [
            'navbar' => C(THEME_BASE_DIR . '/components/navbar', [
                "title" => ENV['title'],
                "items" => implode("\n", array_map(function($v) {
                    return C(THEME_BASE_DIR . '/components/navbarItem', $v);
                }, THEME_CONFIG['navbar']))
            ]),
            "posts" => implode("\n", array_map(function($_post){
                return C(THEME_BASE_DIR . '/components/postContent', $_post);
            }, self::getPosts())),
            'footer' => C(THEME_BASE_DIR . '/components/footer', [
                "busuanzi" => C(THEME_BASE_DIR . '/components/busuanzi')
            ])
        ]);
        $archivesFilePath = DIST_DIR . '/archives.html';
        if (!file_exists($archivesFilePath)) {
            touch($archivesFilePath);
        }
        file_put_contents($archivesFilePath, $html);
    }

    public static function getPosts()
    {
        $postsRes = [];
        $posts = scandir(POST_DIR);
        foreach ($posts as $v) {
            if (stripos($v, '.') !== false) continue;
            $_postPath = POST_DIR . '/' . $v;
            if (is_dir($_postPath)) {
                $mdContent = file_get_contents($_postPath . "/index.md");
                $postSplit = PostTemplate::splitPost($mdContent);
                $postsRes[] = [
                    'path' => "_posts/{$v}/index.html",
                    'title' => $postSplit['title'] ?? '',
                    'datetime' => $postSplit['datetime'] ?? '',
                    'timestamp' => $postSplit['datetime'] ? strtotime($postSplit['datetime']) : 0,
                    'emoji' => $postSplit['emoji']
                ];
            } else if (is_file($_postPath) && stripos($_postPath, ".md") !== false) {
                $mdContent = file_get_contents($_postPath);
                $postSplit = PostTemplate::splitPost($mdContent);
                $postsRes[] = [
                    'path' => "_posts/" . str_replace("md", 'html', $v),
                    'title' => $postSplit['title'] ?? '',
                    'datetime' => $postSplit['datetime'] ?? '',
                    'timestamp' => $postSplit['datetime'] ? strtotime($postSplit['datetime']) : 0,
                    'emoji' => $postSplit['emoji']
                ];
            }
        }

        usort($postsRes, function($a, $b) {
            if (THEME_CONFIG['archivesSort'] == 'desc') {
                return $a['timestamp'] > $b ['timestamp'] ? -1 : 1;
            } else if (THEME_CONFIG['archivesSort'] == 'asc') {
                return $a['timestamp'] < $b ['timestamp'] ? -1 : 1;
            }
            
        });

        return $postsRes;
    }
}
