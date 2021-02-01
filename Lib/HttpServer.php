<?php
namespace Lib;

use Cmd\BuildCmd;

class HttpServer
{
    private static $socket = null;
    private static $port = 0;

    private function __construct() {}

    public function __destruct()
    {
        socket_close(self::$socket);
    }
    
    public static function init()
    {
        if (self::$socket === null) {
            self::$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        }

        self::$port = ENV['develop_server_port'];   
        while (!@socket_bind(self::$socket, ENV['develop_server_host'], self::$port)) {
            echo self::$port . "port used! \n";
            self::$port ++;
        }

        return new self();
    }

    public function listen()
    {
        socket_listen(self::$socket, 4);
        echoLib::echoGreen("Started develop server: http://" 
        . ENV['develop_server_host'] 
        . ":" . self::$port . "\n"
        . "You can use 'php hikari b' in another terminal to rebuild dist files! \n");
        
        while (1) {
            $msg = socket_accept(self::$socket);
            $buf = socket_read($msg, 9999);
            $a = stripos($buf, '/') + 1;
            $b = stripos($buf, ' HTTP');
            $reqPath = substr($buf, $a, $b - $a);
            echo "Request {$reqPath} \n";
            
            $filePath = DIST_DIR . '/' . $reqPath;
            if (is_dir($filePath)) {
                $filePath .= "/index.html";
            }
            if (!is_file($filePath)) {
                echo "Load file error, file not exist! {$filePath}";
            }

            $resText = self::text(@file_get_contents($filePath));

            socket_write($msg,$resText);
            socket_close($msg);
        }
    }

    private static function text($text)
    {
        $length = strlen($text);
        $resText = <<<EOF
HTTP/1.0 200 OK
Content-Type: text/html
Content-Length: {$length}

{$text}
EOF;
        return $resText;
    }
}