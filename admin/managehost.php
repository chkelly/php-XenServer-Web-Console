<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){

$servername = cleanString($_POST['name']);
$location = cleanString($_POST['location']);
$primip = cleanString($_POST['primip']);
$netmask = cleanString($_POST['netmask']);
$gateway = cleanString($_POST['gateway']);
$dns1 = cleanString($_POST['dns1']);
$dns2 = cleanString($_POST['dns2']);
$username = cleanString($_POST['username']);
$password = cleanString($_POST['password']);
$active = cleanString($_POST['active']);
$ips = cleanString($_POST['ips']);


mysql_query("INSERT INTO `hosts` (ip, datacenter, hostname, user, password, netmask, gateway, dns1, dns2) VALUES('$primip','$location','$servername','$username','$password','$netmask','$gateway','$dns1','$dns2')") or die (mysql_error());

}
else{
?>
<form method="post" action=""> 
<table>
    <tr><td>Server Name</td><td><input type="text" name="name"></td><td> Example: VPS1</tr>
    <tr><td>Location</td><td><select name="location">
                                <option>DCA2</option>
                                <option>DCA3</option>
                                <option>SEA2</option>
                            </select></td><td>Location of Server</td></tr>
    <tr><td>Primary IP</td><td><input type="text" name="primip"></td><td>Primary IP Address</td></tr>
    <tr><td>Netmask</td><td><input type="text" name="netmask"></td><td>Netmask</td></tr>
    <tr><td>Gateway</td><td><input type="text" name="gateway"></td><td>Gateway</td></tr>
    <tr><td>DNS-1</td><td><input type="text" name="dns1"></td><td>First DNS Address</td></tr>
    <tr><td>DNS-2</td><td><input type="text" name="dns2"></td><td>Second DNS Address</td></tr>
    <tr><td>Username</td><td><input type="text" name="username"></td><td>Administrative user for connecting with the API</td></tr>
    <tr><td>Password</td><td><input type="password" name="password"></td><td>Password of above user</td></tr>
    <tr><td>Active</td><td><input type="checkbox" name="active"></td><td>If Active then it will automatically assign VMs</td></tr>
    <tr><td>IPs</td><td><textarea name="ips" cols="10" rows="5"></textarea></td><td>Enter IPs to be assigned to VM's here. One IP per Line</td></tr>
    <tr><td>&nbsp;</td><td><input type="submit"></td></tr>
</table>
</form>

<?php
}
?>