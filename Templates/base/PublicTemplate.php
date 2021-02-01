<?php

namespace Templates\base;

use Lib\Base\BaseTemplate;

class PublicTemplate implements BaseTemplate
{
    public static function render($data)
    {
        echo "Building public ..." . PHP_EOL;
        self::handleRender(THEME_BASE_DIR . '/public');
    }

    public static function handleRender($path)
    {
        if (!is_dir($path)) {
            die("Public folder [{$path}] not exist!" . PHP_EOL);
        }
        $files = scandir($path);
        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) continue;
            $filePath = $path . '/' . $file;
            if (is_dir($filePath)) {
                echo "{$filePath} is a dir" . PHP_EOL;
                self::handleRender($filePath);
            }
            if (is_file($filePath)) {
                echo "{$filePath} is a file, copy it..." . PHP_EOL;
                copy($filePath, str_replace(THEME_BASE_DIR . '/public', DIST_DIR, $filePath));
            }
        }
    }
}