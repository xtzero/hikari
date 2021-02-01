<?php


namespace Templates\base;

use Fukuball\Jieba\Jieba;
use Lib\Base\BaseTemplate;
use Lib\Parser;
use Lib\searchLib;

class PostTemplate implements BaseTemplate
{
    public static function render($data)
    {
        Jieba::init();
        self::buildPosts(POST_DIR);
    }

    public static function splitPost($mdFileContent)
    {
        $aStr = "<!--";
        $a = stripos($mdFileContent, $aStr) + 4;
        $bStr = "-->";
        $b = stripos($mdFileContent, $bStr);
        $comments = substr($mdFileContent, $a, $b - $a);
        $commentArr = explode("\n", $comments);
        foreach ($commentArr as $commentLine) {
            if (!stripos($commentLine, "|")) continue;
            list($_k, $_v) = explode("|", $commentLine);
            switch (trim($_k)) {
                case 'title': $_title = trim($_v); break;
                case 'datetime': $_datetime = trim($_v); break;
                case 'emoji': $_emoji = trim($_v); break;
            }
        }
        return [
            'title' => $_title ?? '',
            'datetime' => $_datetime ?? '',
            'emoji' => $_emoji ?? '📒',
            'body' => substr($mdFileContent, stripos($mdFileContent, "-->") + 3),
            'footer' => C(THEME_BASE_DIR . '/components/footer', [
                "busuanzi" => ""
            ])
        ];
    }

    public static function buildPosts($postDir)
    {
        $posts = scandir($postDir);
        $dbData = [];
        foreach ($posts as $v) {
            $filePath = $postDir . '/' . $v;
            if (is_dir($filePath)) {
                echo "{$filePath} is dir \n";
                if (in_array($v, ['.', '..'])) continue;
                if (!is_dir(DIST_DIR . '/_posts/' . $v)) {
                    if (!is_dir(DIST_DIR . '/_posts')) mkdir (DIST_DIR . '/_posts');
                    mkdir(DIST_DIR . '/_posts/' . $v);
                }
                self::buildPosts($filePath);
            } else if(is_file($filePath)) {
                if (stripos($filePath, '.md')) {
                    echo "{$filePath} is markdown file \n";
                    $htmlFilePath = str_replace('.md', '.html', $filePath);
                    $htmlFilePath = str_replace('Sources/Posts', 'Dist/_posts', $htmlFilePath);
                    if (!file_exists($htmlFilePath)) {
                        touch($htmlFilePath);
                    }

                    // 获取md文档内容，分割备注和正文
                    $mdFileContent = file_get_contents($filePath);
                    $postSplit = self::splitPost($mdFileContent);
                    // 用md库将md处理成html
                    $htmlContent = (new Parser())->makeHtml($postSplit['body']);
                    $html = C(THEME_BASE_DIR . '/page/post.html', [
                        'title' => $postSplit['title'],
                        'busuanzi' => C(THEME_BASE_DIR . '/components/busuanzi'),
                        'contentCss' => C(THEME_BASE_DIR . '/components/contentCss'),
                        'navbar' => C(THEME_BASE_DIR . '/components/navbar', [
                            "title" => ENV['title'],
                            "items" => implode("\n", array_map(function($v) {
                                return C(THEME_BASE_DIR . '/components/navbarItem', $v);
                            }, THEME_CONFIG['navbar']))
                        ]),
                        'emoji' => $postSplit['emoji'],
                        'datetime' => $postSplit['datetime'],
                        'content' => $htmlContent,
                        'footer' => $postSplit['footer']
                    ]);
                    file_put_contents($htmlFilePath, $html);

                    if (THEME_CONFIG['enableSearch']) {
                        // 处理分词
                        $htmlWithoutTag = strip_tags($htmlContent);
                        $jiebaRes = Jieba::cut($htmlWithoutTag);
                        foreach($jiebaRes as $v) {
                            $_path = str_replace(DIST_DIR, '', $htmlFilePath);
                            if (isset($dbData[$v]) && in_array($_path, array_column($dbData[$v], 'path'))) continue;
                            $dbData[$v][] = [
                                "path" => $_path,
                                "context" => mb_substr($htmlWithoutTag, mb_stripos($htmlWithoutTag, $v), mb_strlen($v) * 10),
                                'title' => $postSplit['title']
                            ];
                        }
                    }
                } else if (stripos($filePath, '.') !== 36) {
                    echo "{$filePath} is others file \n";
                    $goalFilePath = str_replace('Sources/Posts', 'Dist/_posts', $filePath);
                    copy($filePath, $goalFilePath);
                }
            }

            if (THEME_CONFIG['enableSearch']) searchLib::mergeDbData($dbData);
        }
    }
}
