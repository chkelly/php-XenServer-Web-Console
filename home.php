<div id='bodyContent'>
<?php

echo "<table class='vmtable'>";
echo "<tr><th class='vmth'>VM Name</th><th class='vmth'>IP</th><th class='vmth'>Memory</th><th class='vmth'>Datacenter</th><th class='vmth'>Host Server</th><th class='vmth'>Current Status</th><th class='vmth'>Power Controls</th></tr>";
$uid = $_SESSION['uid'];
$vms = getVMs($uid);
$i = 0;
foreach ($vms as $vm){
    $progress = $vm["progress"];
    $vmid = $vm["id"];
    $name = $vm["name"];
    $datacenter = $vm["datacenter"];
    $hostname = $vm["hostname"];
    $ip = $vm["ip"];
    if ($i == 0){
            $rclass = "reven";
            $i = 1;
        }
        else{
            $rclass = "rodd";
            $i = 0;
        }
    if ($progress == 100){
        $metrics = getVMStatus($vmid);
        $cpu = $metrics["cpu"];
        $memory = $metrics["memory"];
        $power = $metrics["power"];
        echo "<tr class=$rclass><td class='vmtd'><a href='./index.php?a=dashboard&vm=$vmid'>$name</a></td><td class='vmtd'>$ip</td><td class='vmtd'>$memory</td><td class='vmtd'>$datacenter</td><td class='vmtd'>$hostname</td>";
        if ($power == "Halted"){
            $powerimg = "off.png";
        }
        elseif ($power == "Running"){
            $powerimg = "on.png";
        }
        echo "<td class='vmtd' id='status$vmid'><img src='./inc/images/$powerimg' alt='$power' title='$power'></td>";
        
        if ($power == "Halted"){
            echo "<td class='vmtd' id='control$vmid'><a href='#' onClick=power('start',$vmid);><img src='./inc/images/on.png' alt='Power On' title='Power On'></a>";
        }
        elseif ($power == "Running"){
            echo "<td class='vmtd' id='control$vmid'><a href='#' onClick=power('reboot',$vmid);><img src='./inc/images/reboot.png' alt='Reboot' title='reboot'></a><a href='#' onClick=power('shutdown',$vmid);><img src='./inc/images/off.png' alt='Shutdown' title='Shutdown'></a>";
        }
        echo "</tr>";
    }
    elseif ($progress == -100){
        echo "<tr class=$rclass><td class='vmtd'>$name</td><td class='vmtd' colspan=5>Pending Deletion</td></tr>";
    }
    else{
        echo "<tr class=$rclass><td class='vmtd'>$name</td><td class='vmtd' colspan=5>Setup Pending</td></tr>";
    }
}

echo "</table>";
?>
</div>