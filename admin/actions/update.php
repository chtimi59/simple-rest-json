<?php
if (!isset ($_POST['data'])) $_POST['data'] = NULL;

function doUpdate()
{
    if (json_decode($_POST['data'])) {
        $req = "UPDATE `".MYSQL_TABLE_ASSETS."` SET ";
        $req .= "`data` = '".$_POST['data']."', ";
        $req .= "`lastChange` = CURRENT_TIMESTAMP ";
        $req .= "WHERE `id`='".$_GET['id']."'";
        @mysql_query($req) or sqldie($req);
    }
    header("Location: index.php?action=".ACTION_GET."&id=".$_GET['id']);
    return true;
}
?>