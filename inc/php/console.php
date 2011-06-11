<?php
include('xenapi.php');
include('../../functions.php');
include('db.php');
include('../../sessionhandler.php');

$vmid = $_GET['vmid'];

$sql = ("SELECT * from vms where id=$vmid");
$result = mysql_query($sql) or die (mysql_error());
$userid = mysql_result($result,"0","userid");
$hostid = mysql_result($result,"0","hostid");
$vncport = mysql_result($result,"0","vncport");

if ($userid == $_SESSION['uid'] or $_SESSION['admin'] == 1){
    $sql = ("SELECT * from hosts where id=$hostid");
    $result = mysql_query($sql) or die (mysql_error());
    $ip = mysql_result($result,"0","ip");
    $username = mysql_result($result,"0","consoleuser");
    $password = mysql_result($result,"0","consolepass");
?>
<HTML>
<TITLE>
TightVNC desktop
</TITLE>
<BODY>

<APPLET ARCHIVE="TightVncViewer.jar"
        CODE="com.tightvnc.vncviewer.VncViewer"
        WIDTH="640" HEIGHT="480">
<PARAM NAME="SOCKETFACTORY" VALUE="com.tightvnc.vncviewer.SshTunneledSocketFactory">
<PARAM NAME="SSHHOST" VALUE="<?php echo "$username@$ip";?>">
<PARAM NAME="PASSWORD" VALUE="u53rblu35">
<PARAM NAME="HOST" VALUE="localhost">
<PARAM NAME="PORT" VALUE="<?php echo "$vncport"; ?>">
<PARAM NAME="Open New Window" VALUE="no">
</APPLET>
</BODY>
</HTML>


<?php
}
else{
    
    echo "Unauthorized!";
}
?>