<?php


namespace Cmd;


use Lib\Base\BaseCmd;

/**
 * Class CCmd
 * @package Cmd
 *
 * Alias of Clean
 */
class CCmd implements BaseCmd
{
    public static function handle($argv)
    {
        CleanCmd::handle($argv);
    }
}
