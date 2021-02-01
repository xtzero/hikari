<?php


namespace Lib\Base;


interface BaseCmd
{
    /**
     * A command line command must have a `handle` function which is public and static!
     * @return mixed
     */
    public static function handle($argv);
}
