<?php
include("sessionhandler.php");
unset($_SESSION['loggedIn']);
header("Location: login.php");
?>