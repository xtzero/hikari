<?php


namespace Cmd;


use Lib\Base\BaseCmd;

/**
 * Class HCmd
 * @package Cmd
 *
 * Alias of Help
 */
class HCmd implements BaseCmd
{
    public static function handle($argv)
    {
        return HelpCmd::handle($argv);
    }
}
