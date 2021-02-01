<?php
/**
 * 模板渲染方法
 */
function C($filePath, $replaceData = []) {
    if (!file_exists($filePath)) {
        return "";
    }
    $contents = file_get_contents($filePath);
    if (!empty($replaceData)) {
        foreach ($replaceData as $k => $v) {
            $contents = str_replace("{{{$k}}}", $v, $contents);
        }
    }
    return $contents;
}