<table class='vmtable'>
<?php
$sql = ("select datacenter from hosts GROUP BY datacenter ORDER BY datacenter");
$results = mysql_query($sql) or die (mysql_error());
$num = mysql_numrows($results);

$d = 0;
if ($num != 0){
    while ($d < $num){
        $dc = mysql_result($results,$d,"datacenter");
        echo "<tr><th colspan='8' class='vmth'>$dc</th></tr>";
        $d++;
        $sql2 = ("select id,hostname from hosts where datacenter='$dc'");
        $results2 = mysql_query($sql2) or die (mysql_error());
        $num2 = mysql_numrows($results2);
        
        $h = 0;
        if ($num2 != 0){
            while ($h < $num2){
                $hostname = mysql_result($results2,$h,"hostname");
                $hostid = mysql_result($results2,$h,"id");
                echo "<tr><th colspan='8' class='vmth'>$hostname</th></tr>";
                echo "<tr><th class='vmth'>User</th><th class='vmth'>VM Name</th><th class='vmth'>VCPUs</th><th class='vmth'>Allocated Memory</th><th class='vmth'>Production</th><th class='vmth'>Backed Up</th><th class='vmth'>Current Status</th><th class='vmth'>Power Controls</th></tr>";
                $h++;
                
                $sql3 = ("select * from vms where hostid='$hostid' and progress=100");
                $results3 = mysql_query($sql3) or die (mysql_error());
                $num3 = mysql_numrows($results3);
                
                $v = 0;
                if ($num3 !=0){
                    while ($v < $num3){
                        $vmid = mysql_result($results3,$v,"id");
                        $userid = mysql_result($results3,$v,"userid");
                        $username = getUsername($userid);
                        $vm = getVMStatus($vmid);
                        $name = $vm['name'];
                        $cpu = $vm['cpu'];
                        $memory = $vm['memory'];
                        $prod = mysql_result($results3,$v,"production");
                        $backup = mysql_result($results3,$v,"backup");
                        $power = $vm['power'];
                        
                        if ($i == 0){
                            $rclass = "reven";
                            $i = 1;
                        }
                        else{
                            $rclass = "rodd";
                            $i = 0;
                        }
                        echo "<tr class=$rclass><td class='vmtd'>$username</td><td class='vmtd'><a href='./index.php?a=dashboard&vm=$vmid'>$name</a></td><td class='vmtd'>$cpu</td><td class='vmtd'>$memory</td>";
                        if ($prod == True){
                            echo "<td><img src='./inc/images/prod.png'></td>";
                        }
                        else {
                            echo "<td></td>";
                        }
                        
                        if ($backup == True){
                            echo "<td><img src='./inc/images/backup.png'></td>";
                        }
                        else{
                            echo "<td></td>";
                        }
                        
                        if ($power == "Halted"){
                            $powerimg = "off-s.png";
                        }
                        elseif ($power == "Running"){
                            $powerimg = "on-s.png";
                        }
                        echo "<td class='vmtd' id='status$vmid'><img src='./inc/images/$powerimg' alt='$power' title='$power'></td>";
                        
                        if ($power == "Halted"){
                            echo "<td class='vmtd' id='control$vmid'><a href='#' onClick=power('start',$vmid);><img src='./inc/images/on-s.png' alt='Power On' title='Power On'></a>";
                        }
                        elseif ($power == "Running"){
                            echo "<td class='vmtd' id='control$vmid'><a href='#' onClick=power('reboot',$vmid);><img src='./inc/images/reboot-s.png' alt='Reboot' title='reboot'></a><a href='#' onClick=power('shutdown',$vmid);><img src='./inc/images/off-s.png' alt='Shutdown' title='Shutdown'></a>";
                        }
                        echo "</tr>";
                        
                        
                        $v++;
                    }
                }
                
                
            }
        }
        
    }
}

?>


</table>