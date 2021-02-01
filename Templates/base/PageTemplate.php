<?php


namespace Templates\base;

use Exception;
use Fukuball\Jieba\Jieba;
use Lib\Base\BaseTemplate;
use Lib\Parser;
use Lib\searchLib;

class PageTemplate implements BaseTemplate
{
    public static function render($data)
    {
        Jieba::init();
        self::buildPages(PAGE_DIR);
    }

    private static function splitPost($mdFileContent)
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
            'emoji' => $_emoji ?? '📔',
            'body' => substr($mdFileContent, stripos($mdFileContent, "-->") + 3)
        ];
    }

    public static function buildPages($pageDir)
    {
        $posts = scandir($pageDir);
        $dbData = [];
        foreach ($posts as $v) {
            $filePath = $pageDir . '/' . $v;
            if (is_dir($filePath)) {
                echo "{$filePath} is dir \n";
                if (in_array($v, ['.', '..'])) continue;
                if (!is_dir(DIST_DIR . '/' . $v)) {
                    if (!is_dir(DIST_DIR)) mkdir (DIST_DIR . '/_posts');
                    mkdir(DIST_DIR . '/' . $v);
                }
                self::buildPages($filePath);
            } else if(is_file($filePath)) {
                if (stripos($filePath, '.md')) {
                    echo "{$filePath} is markdown file \n";
                    $htmlFilePath = str_replace('.md', '.html', $filePath);
                    $htmlFilePath = str_replace('Sources/Pages', 'Dist', $htmlFilePath);
                    if (!file_exists($htmlFilePath)) {
                        touch($htmlFilePath);
                    }

                    $mdFileContent = file_get_contents($filePath);
                    $postSplit = self::splitPost($mdFileContent);

                    $htmlContent = (new Parser())->makeHtml($postSplit['body']);
                    $html = C(THEME_BASE_DIR . '/page/page.html', [
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
                        'footer' => C(THEME_BASE_DIR . '/components/footer', [
                            "busuanzi" => ""
                        ])
                    ]);
                    file_put_contents($htmlFilePath, $html);

                    // 处理分词
                    if (THEME_CONFIG['enableSearch']) {
                        $htmlWithoutTag = strip_tags($htmlContent);
                        $jiebaRes = Jieba::cut($htmlWithoutTag);
                        foreach($jiebaRes as $v) {
                            $_path = str_replace(DIST_DIR, '', $htmlFilePath);
                            if (isset($dbData[$v]) && in_array($_path, array_column($dbData[$v], 'path'))) continue;
                            $dbData[$v][] = [
                                "path" => $_path,
                                "context" => mb_substr($htmlWithoutTag, mb_stripos($htmlWithoutTag, $v), mb_strlen($v) * 4),
                                'title' => $postSplit['title']
                            ];
                        }
                    }
                } else if (stripos($filePath, '.') !== 36) {
                    echo "{$filePath} is others file \n";
                    $goalFilePath = str_replace('Sources/Pages', 'Dist', $filePath);
                    copy($filePath, $goalFilePath);
                }
            }

            if (THEME_CONFIG['enableSearch']) {
                searchLib::mergeDbData($dbData);
            }
        }
    }
}
