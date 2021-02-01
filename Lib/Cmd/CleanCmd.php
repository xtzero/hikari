<?php


namespace Cmd;


use Lib\Base\BaseCmd;

class CleanCmd implements BaseCmd
{
    public static function handle($argv)
    {
        if (is_dir(DIST_DIR)) {
            self::handleRmFilesOfDir(DIST_DIR);
            echo "Clean dist path finish\n";    
        } else {
            echo "Dist path not exist, finish!" . PHP_EOL;
        }
    }

    public static function handleRmFilesOfDir($path)
    {
        $files = scandir($path);
        foreach ($files as $v) {
            $_filePath = $path . '/' . $v;
            if (in_array($v, ['.', '..'])) continue;
            if (is_dir($_filePath)) {
                echo "{$_filePath} is a path \n";
                self::handleRmFilesOfDir($_filePath);
                rmdir($_filePath);
            }
            if (is_file($_filePath)) {
                echo "{$_filePath} is a file \n";
                unlink($_filePath);
            }
        }
    }
}
