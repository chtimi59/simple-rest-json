<?php
if(!@include("../conf.php")) { echo("Setup missing"); die(); }
include('../defines.php');
if (!file_exists ("../VERSION")) { echo 'missing VERSION file'; die(); }
if (!preg_match('/^([0-9]+)\.([0-9]+)\.([0-9]+)\.(.+)$/', file_get_contents("../VERSION"), $VER_ARR)) { echo 'bad VERSION file'; exit(1); }
$VERSION=$VER_ARR[1].'.'.$VER_ARR[2].'.'.$VER_ARR[3].' (build '.$VER_ARR[4].')';

/* actions */
define('ACTION_NONE',     0);
define('ACTION_CREATE',   1);
define('ACTION_DELETE',   2);
define('ACTION_GET',      3);
define('ACTION_UPDATE',   4);

/* various helpers */
include('actions/helpers.php');
/* actions on assets list */
include('actions/create.php');
include('actions/delete.php');
include('actions/update.php');

/* default post/get values */
if (!isset ($_GET['action']))        $_GET['action'] = ACTION_NONE;
if (!isset ($_GET['id']))            $_GET['id'] = NULL;

/* general user error message */
$USERMSG_TYPE='sucess';
$USERMSG_STR = "";

/* database connection */
if ($GLOBALS['CONFIG']['sql_isPW']) {
	$db = @mysql_connect($GLOBALS['CONFIG']['sql_host'], $GLOBALS['CONFIG']['sql_login'], $GLOBALS['CONFIG']['sql_pw']); 
} else {
	$db = @mysql_connect($GLOBALS['CONFIG']['sql_host'], $GLOBALS['CONFIG']['sql_login']); 
} 
if (!$db) die('Could not connect: ' . mysql_error());
if(!mysql_select_db($GLOBALS['CONFIG']['sql_db'],$db)) die('Could not connect db: ' . mysql_error());

/* main action switch */
switch($_GET['action']) {
    case ACTION_NONE:
        break;
    case ACTION_UPDATE:
        doUpdate(); exit();
        break;
    case ACTION_GET:
        include('actions/get.php'); exit();
        break;
    case ACTION_CREATE:
        if (doCreate()) endOk();
        break;
    case ACTION_DELETE: 
        if(doDelete()) endOk();
        break;
}

/* count nb of entry */
$sql = 'SELECT COUNT(*) FROM `'.MYSQL_TABLE_ASSETS.'`';
$req = @mysql_query($sql) or sqldie($sql);
$row = mysql_fetch_assoc($req);
$count = $row['COUNT(*)'];
?>

<html>
<link rel="stylesheet" href="css/html.css">
<link rel="stylesheet" href="css/buttons.css">
<link rel="stylesheet" href="css/table.css">
<link rel="stylesheet" href="css/usermsg.css">
<body>

<!-- ERROR MSG -->
<?php if (!empty($USERMSG_STR)) {
    echo "<div id='usermsg' class='$USERMSG_TYPE'>$USERMSG_STR</div>\n";
    echo "<script>\n";
    echo "setTimeout(function() { document.getElementById('usermsg').className += ' load'; }, 100)\n";
    echo "setTimeout(function() { document.getElementById('usermsg').className += ' unload'; }, 3000)\n";
    echo "</script>\n";
}
?>

<h1>Admin Page</h1>
<p>version: <?php echo $VERSION?></p>
<button onclick="location.href='index.php?action=<?PHP echo ACTION_CREATE?>';">New UUID</button>

<!-- RESULT TABLE -->
<?php
if ($count != 0)
{
    /* by default sort by id */
    $sortBy = 'creation';
    $sortAsc = 1;
    if (isset ($_GET['sortBy'])) $sortBy = $_GET['sortBy'];
    if (isset ($_GET['sortAsc'])) $sortAsc = $_GET['sortAsc'];
    $newSortAsc = ($sortAsc)?0:1;
    
    echo "<table>\n";

    /* table header */
    echo "<tr>\n";
    echo "<th>UUID</th>\n";
    echo "<th onclick=\"location.href='index.php?sortBy=creation&sortAsc=$newSortAsc'\">creation</th>\n";
    echo "<th onclick=\"location.href='index.php?sortBy=lastChange&sortAsc=$newSortAsc'\">lastChange</th>\n";
    echo "<th></th>\n";
    echo "</tr>\n";

    /* table contents */
    $sql = 'SELECT * FROM `'.MYSQL_TABLE_ASSETS.'` ORDER BY `'.$sortBy.'` '.($sortAsc?'ASC':'DESC');
    $req = @mysql_query($sql) or sqldie($sql);
    while ($row = mysql_fetch_assoc($req)) { 
        echo "<tr>\n";
        foreach ($row as $key => $value) {
            switch($key)
            {
                case 'id': {
                    $url = "index.php?action=".ACTION_GET."&id=".$value;
                    echo "
                        <td class='id'>
                            <a href='$url' target='popup'
                                onclick=\"window.open('$url','popup','status=0,toolbar=0,location=0,menubar=0,directories=0,resizable=1,scrollbars=1,height=910,width=900,'); return false;\"
                            >$value</a>
                        </td>\n";
                    break;
                }
                case 'data':
                    break;
                default:
                    echo "<td>$value</td>";
            }
        }
        echo "<td class='buttons'>";
        $deleteurl = "index.php?action=".ACTION_DELETE."&id=".$row['id'];
        echo "<a href='$deleteurl'>Delete</a>\n";
        $easyurl = "easy.php?id=".$row['id'];
        echo "<a href='$easyurl'>Wizz</a>\n";
        echo "</td>\n";
        echo "</tr>\n";
    }
    
    echo "</table>\n";
}
?>
</body>
</html>
