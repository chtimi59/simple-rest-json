<?php

/* reload index.php without params */
function endOk() {
    header('Location: index.php');
    exit();
}

/* Die if sql error */
function sqldie($sql) {
    echo("'$sql'<br>\n<br>\n");
    die(mysql_error());
}

/* create guid RFC 4122 */
function guid(){
    if (function_exists('com_create_guid')){
        return trim(com_create_guid(), '{}');
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
         $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
        return $uuid;
    }
}
?>
