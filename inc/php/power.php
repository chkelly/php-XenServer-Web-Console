<?php
include('xenapi.php');
include('../../functions.php');
include('db.php');
$vmID = $_GET['id'];
$action = $_GET['action'];

$vmuuid = getVMUUID($vmID);
$host = getVMHost($vmID);
$xenserver = new XenApi($host["url"], $host["login"], $host["password"]);
$vm = $xenserver->VM__get_by_uuid($vmuuid);

if ($action == "start"){
    $xenserver->VM__start($vm, False, True);
    echo "<img src=./inc/images/on.png alt='Running' title='Running'>";
}
if ($action == "reboot"){
    $xenserver->VM__hard_reboot($vm);
    echo "<img src=./inc/images/on.png alt='Running' title='Running'>";
}
if ($action == "shutdown"){
    $xenserver->VM__hard_shutdown($vm);
    echo "<img src=./inc/images/off.png alt='Shutdown' title='Shutdown'>";
}
?>