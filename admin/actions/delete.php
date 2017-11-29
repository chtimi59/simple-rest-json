<?php
function doDelete()
{
    if ($_GET['id'] == NULL){
        $GLOBALS['USERMSG_TYPE'] = 'error';
        $GLOBALS['USERMSG_STR'] = 'Invalid id';
        return false;
    }

    $sql = "DELETE FROM `".MYSQL_TABLE_ASSETS."` WHERE `id` = '".$_GET['id']."'";
    $req = @mysql_query($sql) or sqldie($sql);  ;
    $row = @mysql_fetch_assoc($req);
    return true;
}
?>