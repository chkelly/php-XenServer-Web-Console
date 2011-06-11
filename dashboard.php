<div id='bodyContent'>
<?php
$vmID = $_GET['vm'];
$metrics = getVMStatus($vmID);
$name = $metrics["name"];
$cpu = $metrics["cpu"];
$memory = $metrics["memory"];
$power = $metrics["power"];
echo "<div class='vmstatus'>";
echo "<table class='vmdashtable'>";
echo "<tr><th class='vmth'>$name Status</th><th class='vmth'>VCPUs</th><th class='vmth'>Allocated Memory</th></tr>";
echo "<tr>";
if ($power == "Halted"){
        $powerimg = "off.png";
    }
    elseif ($power == "Running"){
        $powerimg = "on.png";
    }
echo "<td class='vmtd' id='status$vmID'><img src='./inc/images/$powerimg' alt='$power' title='$power'></td>";
echo "<td class='vmtd'>$cpu</td><td class='vmtd'>$memory MB</td>";
echo "</tr>";
echo "</table>";
echo "</div>";

echo "<div class='vmcontrol'>";
echo "<table class='vmdashtable'>";
echo "<tr><th class='vmth'>Power Controls</th></tr>";
echo "<tr>";
if ($power == "Halted"){
        echo "<td class='vmtd' id='control$vmID'><a href='#' onClick=power('start',$vmID);><img src='./inc/images/on.png' alt='Power On' title='Power On'></a>";
    }
    elseif ($power == "Running"){
        echo "<td class='vmtd' id='control$vmID'><a href='#' onClick=power('reboot',$vmID);><img src='./inc/images/reboot.png' alt='Reboot' title='reboot'></a><a href='#' onClick=power('shutdown',$vmID);><img src='./inc/images/off.png' alt='Shutdown' title='Shutdown'></a>";
    }
    echo "</tr>";
echo "</table>";
echo "</div>";

echo "<div style='clear: both;'> </div>";

echo "<div class='vmstatus'>";
echo "<table class='vmdashtable'>";
echo "<tr><th colspan='2' class='vmth'>Manage VM</th></tr>";
echo "<td><a href='./removevm.php?vm=$vmID'><img src='./inc/images/remove-l.png' alt='Remove VM' title='Remove VM'></a><a href='#' onClick=launchConsole($vmID);><img src='./inc/images/console.png'></a></td>";
echo "</tr>";
echo "</table>";
echo "</div>";


echo "<div class='vmcontrol'>";
echo "<table class='vmdashtable'>";
echo "<tr><th class='vmth'>Modify VM</th></tr>";
echo "<tr><td>The VM Must be shutdown first.</td></tr>";
echo "</table>";
echo "</div>";

echo "<div style='clear: both;'> </div>";

echo "<table id='vmlogs' class='vmLogs'>";
echo "<tr><th class='vmth'>Date</th><th class='vmth'>Action</th><th class='vmth'>Status</th></tr>";
echo "<tr><td colspan='3' class='vmtd'>No Log Data</td></tr>";

echo "</table>";
?>
</div>