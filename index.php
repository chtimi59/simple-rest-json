<?php

function die_sqlerror($msg="") {
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    $dbg =  "[".$_SERVER['REMOTE_ADDR']."] ";
    $dbg .= "[".$caller['file'].":".$caller['line']."] ";
	$err = mysql_error();
    error_log($dbg);
    error_log($sql);
    error_log($err);
    header('HTTP/1.1 500 Internal error', true, 500);
    exit($msg);
}

function die_internal_error($msg="") {
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    $dbg =  "[".$_SERVER['REMOTE_ADDR']."] ";
    $dbg .= "[".$caller['file'].":".$caller['line']."] ";
    $dbg .= $msg;
    error_log($dbg);
    header('HTTP/1.1 500 Internal error', true, 500);
    exit($msg);
}

function die_bad_request($msg="") {
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    $dbg =  "[".$_SERVER['REMOTE_ADDR']."] ";
    $dbg .= "[".$caller['file'].":".$caller['line']."] ";
    $dbg .= $msg;
    error_log($dbg);
    header('HTTP/1.1 400 Bad Request', true, 400);
    exit($msg);
}

function die_forbidden($msg="") {
    header('HTTP/1.1 403 Forbidden', true, 403);
    exit($msg);
}

if(!@include("conf.php")) { die_internal_error("Setup missing"); }
include('defines.php');
if (!file_exists ("VERSION")) { die_internal_error('missing VERSION file'); }
if (!preg_match('/^([0-9]+)\.([0-9]+)\.([0-9]+)\.(.+)$/', @file_get_contents("VERSION"), $VER_ARR)) { die_internal_error('bad VERSION file'); }
$VERSION=$VER_ARR[1].'.'.$VER_ARR[2].'.'.$VER_ARR[3].' (build '.$VER_ARR[4].')';
if (!isset ($_GET['id'])) die_bad_request('id mssing');

/* database connection */
if ($GLOBALS['CONFIG']['sql_isPW']) {
	$db = @mysql_connect($GLOBALS['CONFIG']['sql_host'], $GLOBALS['CONFIG']['sql_login'], $GLOBALS['CONFIG']['sql_pw']); 
} else {
	$db = @mysql_connect($GLOBALS['CONFIG']['sql_host'], $GLOBALS['CONFIG']['sql_login']); 
} 
if (!$db) die_sqlerror("database connection error");
if(!mysql_select_db($GLOBALS['CONFIG']['sql_db'],$db)) die_sqlerror("database error");

/* servicing */
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        break;
    case 'PUT':
    case 'POST':
        $json = file_get_contents('php://input');
        $post_data = json_decode($json,true); // $post_data==NULL if not data
        if ($post_data!=NULL) { $_POST=$post_data; } // overwrite POST
        if (isset ($_POST['data'])) $_DATA=$_POST['data'];
        break;
    default:
        die_bad_request('invalid method');
        break;
}

$sql = "SELECT * FROM `".MYSQL_TABLE_ASSETS."` WHERE `id`='".$_GET['id']."'";
$req = @mysql_query($sql) or sqldie($sql);  ;
$row = @mysql_fetch_assoc($req);
if (!$row) die_bad_request('invalid id');
header('HTTP/1.1 200 OK', true, 200);
print json_encode($row);
