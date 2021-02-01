<?php


namespace Cmd;


use Lib\Base\BaseCmd;

/**
 * Class SCmd
 * @package Cmd
 *
 * Alias of Serve
 */
class SCmd implements BaseCmd
{
    public static function handle($argv)
    {
        ServeCmd::handle($argv);
    }
}
