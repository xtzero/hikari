<?php
namespace Lib;

class searchLib
{
    public static function mergeDbData($dbData)
    {
        if (!$dbData || empty($dbData)) {
            return false;
        }

        $dbFilePath = DIST_DIR . '/db.json';
        if (!is_file($dbFilePath)) {
            touch($dbFilePath);
        }

        $_json = json_decode(file_get_contents($dbFilePath), true);
        foreach ($dbData as $k => $v) {
            foreach ($v as $vv) {
                $_json[$k][] = $vv;
            }
        }
        $jsonRes = json_encode($_json);
        file_put_contents($dbFilePath, $jsonRes);
    }
}