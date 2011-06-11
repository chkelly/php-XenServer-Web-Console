<?php
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include ('functions.php');
    $username  = cleanString($_POST['username']);
    $password = cleanString($_POST['password']);
    include('./inc/php/db.php');
    $sql = ("select * from users where user='$username'");
    $result = mysql_query($sql) or die (mysql_error());
    $dbid = mysql_result($result,"0","id");
    $dbpass = mysql_result($result,"0","pass");
    
    if (empty($username) || empty($password)){
	$error = "You must enter a username and password";
    }
    elseif (isset($username) and $dbpass == $password){
        $_SESSION['uid'] = $dbid;
        $_SESSION['admin'] = 1;
        $_SESSION['loggedIn'] = True;
        header("location: index.php");
    }
    else{
        $error = "Your Login Name or Password was invalid";
    }
}
?>

<html>
    <head>
        <title>VPS Login</title>
    </head>
<body>
<html>
    <head>
        <title>VPS Login</title>
        <link href="./inc/css/main.css" rel="stylesheet" type="text/css">
    </head>
<body>
    <div id="loginVert">
        <div id="loginHoz">
            <form action="" method="post">
                <table width="100%">
                    <?php
                    if(isset($error)){
                        echo "<tr><td class='loginError'>$error</td></tr>";
                    }
                    ?>
                    <tr><td><label class="loginTxt" for="username">Username</label></td></tr>
                    <tr><td><input class="loginInput" type="text" name="username"</td></tr>
                    <tr><td><label class="loginTxt" for="password">Password</label></td></tr>
                    <tr><td><input class="loginInput" type="password" name="password"></td></tr>
                    <tr><td><input class="button red" type="submit" value="Login"></td></tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>

