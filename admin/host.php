<table class='vmtable'>
        <tr> 
            <th class='vmth'>Server</th>
            <th class='vmth'>Location</th>
            <th class='vmth'>IP</th>
            <th class='vmth'>Total Memory</th>
            <th class='vmth'>Free Memory</th>
            <th class='vmth'>VMs</th>
            <th class='vmth'>Active</th>
            <th class='vmth'>Status</th>
            
        </tr>
        
<?php
$sql = ("SELECT * FROM `hosts` ORDER BY datacenter DESC");
$results = mysql_query($sql) or die (mysql_error());
$num = mysql_numrows($results);

$i = 0;
if ($num != 0){
    while ($i < $num){
        $id = mysql_result($results,$i,"id");
        $ip = mysql_result($results,$i,"ip");
        $datacenter = mysql_result($results,$i,"datacenter");
        $hostname = mysql_result($results,$i,"hostname");
        $memory = convbytes(mysql_result($results,$i,"memory"), "MB");
        $active = mysql_result($results,$i,"active");
        $freemem = convbytes(mysql_result($results,$i,"freemem"), "MB");
        $vms = mysql_result($results,$i,"vms");
        $status = mysql_result($results,$i,"status");
        
        echo "<tr><td>$hostname</td><td>$datacenter</td><td>$ip</td><td>$memory MB</td><td>$freemem MB</td><td>$vms</td>";
        
        if ($active == True){
                echo "<td><img src='./inc/images/active.png'></td>";
        }
        else {
                echo "<td><img src='./inc/images/inactive.png'></td>";
        }
        
        if ($status == 0){
                echo "<td><img src='./inc/images/off.png'></td>";
        }
        elseif ($status == 1){
                echo "<td><img src='./inc/images/on.png'></td>";
        }
        elseif ($status == 2){
                echo "<td><img src='./inc/images/warning.png'></td>";
        }
        $i++;
    }
}    

?>
    </table>
<p><a href='./?a=admin&t=managehost'>Add Host Server</a></p>
