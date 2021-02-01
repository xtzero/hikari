<?php
if ($argc == 1) {
    die('Did nothing, please run `php hikari help`');
}
$command = $argv[1];
$className = strtoupper(substr($command, 0, 1)) . strtolower(substr($command, 1)) . 'Cmd';
$cmdFile = CMD_DIR . "/" . $className . '.php';
if (!file_exists($cmdFile)) {
    die("command `{$command}` doesn't exist!");
}
require "./vendor/autoload.php";
$class = "Cmd\\{$className}";

require_once("functions.php");

$class::handle($argv);
