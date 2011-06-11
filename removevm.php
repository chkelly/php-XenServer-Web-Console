<?php
include("sessionhandler.php");
include("functions.php");
include('./inc/php/db.php');
$vmid = cleanString($_GET['vm']);
$sql = ("SELECT userid FROM vms WHERE id=$vmid");
$result = mysql_query($sql) or die (mysql_error());
$userid = mysql_result($result,"0","userid");

if ($userid == $_SESSION['uid'] or $_SESSION['admin'] == 1){
    mysql_query("UPDATE vms SET progress='-100' WHERE id=$vmid") or die (mysql_error());
       
}

header("location: index.php");

?>