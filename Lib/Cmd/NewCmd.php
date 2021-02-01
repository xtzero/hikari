<?php


namespace Cmd;


use Lib\Base\BaseCmd;

class NewCmd implements BaseCmd
{
    public static function handle($argv)
    {
        self::init();
        $type = $argv[2] ?? "";
        $filename = $argv[3] ?? "";
        if (empty($filename)) {
            fwrite(STDOUT, "Please input file name: [". time() .".md]");
            $stdin = fgets(STDIN);
            if (!$stdin || $stdin == "\n") {
                $filename = time() . '.md';
            } else {
                $filename = $stdin;
            }
        }

        beginSwitch:
        if (!in_array($type, ['post', 'page'])) {
            fwrite(STDOUT, "Please input [post|page|others for cancel]:");
            $type = substr(fgets(STDIN), 0, -1);
            if (in_array($type, ['post', 'page'])) {
                goto beginSwitch;
            } else {
                die('Bye~');
            }
        }
        $filename = str_replace(["\n", " ", "\t", "\r"], "", $filename);
        switch ($type) {
            case 'post': self::handleNewPost($filename, $argv); break;
            case 'page': self::handleNewPage($filename, $argv); break;
        }
    }

    public static function handleNewPost($filename, $argv)
    {
        self::handleNewFile($filename, 'Posts');
    }

    public static function handleNewPage($filename, $argv)
    {
        self::handleNewFile($filename, 'Pages');
    }

    private static function handleNewFile($filename, $category)
    {
        $mdFolder = ROOT_DIR . "/Sources/{$category}/{$filename}";
        if (is_dir($mdFolder)) {
            die("Folder exist! {$mdFolder}" . PHP_EOL);
        }
        $mdFilePath = "{$mdFolder}/index.md";
        if (file_exists($mdFilePath)) {
            die("File already exist: {$mdFilePath}" . PHP_EOL);
        }
        mkdir($mdFolder);
        touch($mdFilePath);
        $defaultContent = "<!--
title|{$filename}
datetime|".date('Y-m-d H:i:s')."
-->";
        file_put_contents($mdFilePath, $defaultContent);
        die("Create file success: {$mdFilePath}" . PHP_EOL);
    }

    public static function init()
    {
        $postPath = POST_DIR;
        $pagePath = PAGE_DIR;
        if (!is_dir($postPath)) {
            mkdir($postPath);
        }
        if (!is_dir($pagePath)) {
            mkdir($pagePath);
        }
    }
}
