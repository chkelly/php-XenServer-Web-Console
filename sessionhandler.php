<?php
session_start();
if($_SESSION['loggedIn'] != True){
    header("Location: login.php");
}

?>