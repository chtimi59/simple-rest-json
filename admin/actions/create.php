<?php

function doCreate()
{
    $req =  "INSERT INTO `".MYSQL_TABLE_ASSETS."` (`id`, `creation`, `lastChange`, `data`) VALUES (";
        $req .= "'".guid()."', ";
        $req .= "CURRENT_TIMESTAMP, ";
        $req .= "CURRENT_TIMESTAMP, ";
        $req .= "'{\"foo\": \"hello world!\"}'";
    $req .= ")";
    @mysql_query($req) or sqldie($req);
    return true;
} 

?>