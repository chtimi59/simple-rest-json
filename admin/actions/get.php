<?php
function doGet()
{
    $sql = "SELECT * FROM `".MYSQL_TABLE_ASSETS."` WHERE `id`='".$_GET['id']."'";
    $req = @mysql_query($sql) or sqldie($sql);  ;
    $row = @mysql_fetch_assoc($req);
    if (! $row) {
        echo "error";
        exit();
    }
    $data = $row['data'];
    $api = "";
    $api .= "Get data: \n";
    $api .= "curl ".$GLOBALS['CONFIG']['base_url']."/?id=".$row['id']."\n";
    $api .= "Set data: \n";
    $api .= "curl ".$GLOBALS['CONFIG']['base_url']."/?id=".$row['id']."\n";
    echo "
    <html>
    <head>
        <style>
        textarea {
            display: block;
            width: 100%;
            border: none;
            height: 33%;
        }
        </style>
    </head>
    ";
    echo "<h1>data:</h1>";
    echo "<textarea>$data</textarea>";
    echo "<h1>API:</h1>";
    echo "<textarea>$api</textarea>";
    echo "
    </html>
    ";
}
?>