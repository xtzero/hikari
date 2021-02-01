<?php


namespace Cmd;


use Lib\Base\BaseCmd;
use Lib\HttpServer;

class ServeCmd implements BaseCmd
{
    public static function handle($argv)
    {
        echo "Exec php hikari b \n\n";
        BuildCmd::handle($argv);
        echo "Exec php hikari s \n\n";
        HttpServer::init()->listen();
    }
}
