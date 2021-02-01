<?php


namespace Cmd;


use Lib\Base\BaseCmd;

class BuildCmd implements BaseCmd
{
    public static function handle($argv)
    {
        CleanCmd::handle($argv);

        if(!is_dir(DIST_DIR)) {
            mkdir(DIST_DIR);
            echo "Mkdir success! " . DIST_DIR;
        }
        $themePath = ROOT_DIR . '/Templates/' . ENV['template'];
        echo "Load theme from path: " . $themePath . "\n";
        if (!is_dir($themePath)) {
            die("{$themePath} not exist!\n");
        }

        $buildSort = THEME_CONFIG['build']['builder'];
        foreach ($buildSort as $v) {
            $v::render([]);
        }
        echo "See at ". DIST_DIR ." \n";
    }
}
