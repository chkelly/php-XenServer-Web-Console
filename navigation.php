<div id='navContent'>
<?php
if ($a == "admin"){
    echo "<div class='navAdmin'>";
    echo "<p>";
    if ($t == "host" or is_null($t)){
        echo "<a class='buttonactive' href='./?a=admin&t=host'><span>Host Server Management</span></a>";
    }
    else {
        echo "<a class='button' href='./?a=admin&t=host'><span>Host Server Management</span></a>";
    }
    
    if ($t == "vm"){
        echo "<a class='buttonactive' href='./?a=admin&t=vm'><span>Virtual Machine Management</span></a>";
    }
    else{
        echo "<a class='button' href='./?a=admin&t=vm'><span>Virtual Machine Management</span></a>";
    }
    
    if ($t == "user"){
        echo "<a class='buttonactive' href='./?a=admin&t=user'><span>User Management</span></a>";
    }
    else {
        echo "<a class='button' href='./?a=admin&t=user'><span>User Management</span></a>";
    }
    echo "</p>";
    
    echo "</div>";
}
?>
<div class='navMenu'>
<p><a href='./'><img src="./inc/images/home.png" alt="Home" title="Home"></a>
<?php
if ($_SESSION['loggedIn'] == True){
    echo "<a href='./?a=addvm'><img src='./inc/images/add.png' alt='Add VM' title='Add VM'></a>";
    if ($_SESSION['admin'] == 1){
        echo "<a href='./?a=admin'><img src='./inc/images/admin.png' alt='Administration Panel' title='Administration Panel'></a>";
    }
    echo "<a href='./logout.php'><img src='./inc/images/logout.png' alt='Logout' title='logout'></a>";
}
?>
</p>
</div>
</div>