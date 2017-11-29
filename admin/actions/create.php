<?php

function doCreate()
{
    $req =  "INSERT INTO `".MYSQL_TABLE_ASSETS."` (`id`, `data`) VALUES (";
    $req .= "'".guid()."', ";
    $req .= "'{}' ";
    $req .= ")";
    @mysql_query($req) or sqldie($req);
    return true;
} 

?>