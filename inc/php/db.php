<?php
//This is included on any page that connects to the database.
require_once "config.php";
$db_host=$dbConfig['host'];
$db_username=$dbConfig['username'];
$db_password=$dbConfig['password'];
$db_name=$dbConfig['db_name'];
mysql_connect("$db_host", "$db_username", "$db_password")or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");
?>