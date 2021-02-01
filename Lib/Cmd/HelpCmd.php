<?php


namespace Cmd;


use Lib\Base\BaseCmd;

class HelpCmd implements BaseCmd
{
    public static function handle($argv)
    {
        echo <<<EOF
 _   _   _   _                     _
| | | | (_) | | __   __ _   _ __  (_)
| |_| | | | | |/ /  / _` | | '__| | |
|  _  | | | |   <  | (_| | | |    | |
|_| |_| |_| |_|\_\  \__,_| |_|    |_|
                                      
Welcome to use hikari blog!
This is a blog system written by a white cat's father!

Usage: php hikari \<command\>
Here are main of commands: 

- php hikari help | h: Get help(just like you now!)
- php hikari new \<post|page\> \<title\>: Create a new post
- php hikari build | b: Build static files
- php hikari serve | s: Start develop server, and check page render when file change
- php hikari clean | c: Clean static files and db file

For others command, you can visit Lib/Cmd folder.

If you want join us, visit https://gitee.com/xtzero/hikari.

Enjoy yourself~ 
EOF . PHP_EOL;
    }
}
