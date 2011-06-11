<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
error_reporting(E_ALL);
$a = $_GET['a'];
$t = $_GET['t'];
include("sessionhandler.php");
include('./inc/php/db.php');
include('./inc/php/xenapi.php');
include('./functions.php');
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>VPS Management Console</title>
    <link href="./inc/css/main.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./inc/js/power.js"></script>
    <script type="text/javascript" src="./inc/js/console.js"></script>
</head>

<body class='body'>

<div id='page-container'>
<div class='padding'>
<div id='header'>
<?php
   require_once 'header.php';
?>
</div>
</div>
<div  class='padding'>
<div id='content'>
<?php
    require_once 'navigation.php';
        if ($a == "dashboard") {
             $body = "dashboard.php";
        }
        elseif ($a == "addvm"){
            $body = "addvm.php";
        }
        elseif ($a == "admin") {
            $body = "./admin/admin.php";
        }
        else {
             $body = "home.php";
        }
   require_once $body;
?>
</div>
</div>
<div class='padding'>
<div id='footer'>
<?php
   require_once 'footer.php';
?>
</div>
</div>
</div>
</body>
</html>     