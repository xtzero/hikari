<?php


namespace Cmd;


use Lib\Base\BaseCmd;

/**
 * Class BCmd
 * @package Cmd
 *
 * Alias of Build
 */
class BCmd implements BaseCmd
{
    public static function handle($argv)
    {
        BuildCmd::handle($argv);
    }
}
