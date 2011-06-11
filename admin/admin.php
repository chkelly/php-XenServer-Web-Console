<?php
$t = $_GET['t'];

if ($t == "vm"){
    $body = "./admin/vm.php";
}
elseif ($t == "user"){
    $body = "./admin/user.php";
}
elseif ($t == "managehost"){
    $body = "./admin/managehost.php";
}
else{
    $body = "./admin/host.php";
}

?>
<div id="adminContent">
    
<?php

require_once $body;

?>
</div>