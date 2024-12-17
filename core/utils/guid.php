<?php

function get_guid()
{
    $guid = '';

    if (function_exists('com_create_guid')) {
        $guid = com_create_guid();
    } else {
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
        $guid = $uuid;
    }

    $guid = str_replace("{", "", $guid);
    $guid = str_replace("}", "", $guid);

    return $guid;
}
