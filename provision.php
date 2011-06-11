<?php
include("sessionhandler.php");
include("functions.php");
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include('./inc/php/db.php');
    $userid = cleanString($_SESSION['uid']);
    $name = cleanString($_POST['name']);
    $memory = cleanString($_POST['memory']);
    $hostid = cleanString($_POST['host']);
    $os = cleanString($_POST['os']);
    $sql = ("select * from ips where vmid = 0 AND hostid=$hostid LIMIT 1");
    $result = mysql_query($sql) or die (mysql_error());
    $id =  mysql_result($result,"0","id");
    
    mysql_query("INSERT INTO `vms` (userid,hostid,name,memory,os,progress) VALUES('$userid','$hostid','$name','$memory','$os','0')") or die (mysql_error());

    $sql = ("SELECT MAX(id) FROM vms WHERE userid=$userid");
    $result = mysql_query($sql) or die (mysql_error());
    $vmid = mysql_fetch_array($result);
    $vmid = $vmid[0];

    mysql_query("UPDATE ips SET vmid=$vmid WHERE id=$id");
    header("location: index.php");
    
}

?>
