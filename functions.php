<?php
//Clean database string
function cleanString($string){
    $resarray = array();
        foreach (func_get_args() as $ourvar) {
            if (!get_magic_quotes_runtime() && !is_array($ourvar)) {
                $ourvar = addslashes($ourvar);
            }
            // Add to array
            array_push($resarray, $ourvar);
        }
        // Return vars
        if (func_num_args() == 1) {
            return $resarray[0];
        } else {
            return $resarray;
        }
}

//Return list of hostservers
function getHosts(){
    $sql = ("SELECT * FROM `hosts` ORDER BY datacenter DESC");
    $results = mysql_query($sql) or die (mysql_error());
    $num = mysql_numrows($results);
    $i = 0;
    if ($num != 0){
        while ($i < $num){
            $hosts[] = array("id" => mysql_result($results,$i,"id"),
                                  "ip" => mysql_result($results,$i,"ip"),
                                  "datacenter" => mysql_result($results,$i,"datacenter"),
                                  "hostname" => mysql_result($results,$i,"hostname"),
                                  "memory" => mysql_result($results,$i,"memory"),
                                  "user" => mysql_result($results,$i,"user"),
                                  "password" => mysql_result($results,$i,"password"),
                                  "active" => mysql_result($results,$i,"active")
                                  );
            $i++;
        }
    }

    return($hosts);
}
//Return list of VMs
function getVMs($userid){
    $userid = cleanString($userid);
    $sql = ("SELECT * FROM `vms` WHERE userid=$userid");
    $results = mysql_query($sql) or die (mysql_error());
    $num = mysql_numrows($results);
    $i = 0;
    if ($num != 0){
        while ($i < $num){
            $id = mysql_result($results,$i,"id");
            $userid = mysql_result($results,$i,"userid");            
            $hostid = mysql_result($results,$i,"hostid");
            $progress = mysql_result($results,$i,"progress");
            $name = mysql_result($results,$i,"name");
            //take the hostid and look up the hostname
            $sql = ("SELECT * FROM `hosts` WHERE id=$hostid");
            $host = mysql_query($sql) or die (mysql_error());
            $hostname = mysql_result($host,0,"hostname");
            $datacenter = mysql_result($host,0,"datacenter");
            $uuid = mysql_result($results,$i,"uuid");
            $production = mysql_result($results,$i,"production");
            $backup = mysql_result($results,$i,"backup");
            
            $sql = ("SELECT * FROM `ips` WHERE vmid='$id'");
            $result = mysql_query($sql) or die (mysql_query());
            $vmip = mysql_result($result,"0","ip");
            $vms[] = array("id" => $id,
                                "userid" => $userid,
                                "hostid" => $hostid,
                                "hostname" => $hostname,
                                "datacenter" => $datacenter,
                                "uuid" => $uuid,
                                "production" => $production,
                                "backup" => $backup,
                                "progress" => $progress,
                                "ip" => $vmip,
                                "name" => $name
                               );
                        
            $i++;            
        }
    }
    return($vms);
}

//query xenserver VM resides on for current status
function getVMStatus($vmID){
    $vmID = cleanString($vmID);
    include_once("./inc/php/xenapi.php");
    $sql = ("SELECT * FROM `vms` WHERE id=$vmID");
    $result = mysql_query($sql) or die (mysql_error());
    $hostid = mysql_result($result,0,"hostid");
    $uuid = mysql_result($result,0,"uuid");
    
    $sql = ("SELECT * FROM `hosts` WHERE id=$hostid");
    $result = mysql_query($sql) or die (mysql_error());
    
    $ip = mysql_result($result,0,"ip");
    $url = "https://$ip";
    $login = mysql_result($result,0,"user");
    $password = mysql_result($result,0,"password");
    
    $xenserver = new XenApi($url, $login, $password);
    $vm = $xenserver->VM__get_by_uuid($uuid);
    $record = $xenserver->VM__get_record($vm);
    $name = $record["name_label"];
    $cpu = $record["VCPUs_max"];
    $memory = $record["memory_static_max"];
    $memory = $memory / 1048576;
    $power = $record["power_state"];
    
    $metrics = array("name" => $name,
                       "cpu" => $cpu,
                       "memory" => $memory,
                       "power" => $power
                       );
    return ($metrics);
}

//get UUID of VM
function getVMUUID($vmID){
    $vmID = cleanString($vmID);
    $sql = ("SELECT uuid FROM `vms` WHERE id=$vmID");
    $result = mysql_query($sql) or die (mysql_error());
    $uuid = mysql_result($result,0,"uuid");
    
    return($uuid);
}

//get connection details for vm's host

function getVMHost($vmID){
    $vmID = cleanString($vmID);
    $sql = ("SELECT * FROM `vms` WHERE id=$vmID");
    $result = mysql_query($sql) or die (mysql_error());
    $hostid = mysql_result($result,0,"hostid");
    
    $sql = ("SELECT * FROM `hosts` WHERE id=$hostid");
    $result = mysql_query($sql) or die (mysql_error());
    
    $ip = mysql_result($result,0,"ip");
    $url = "https://$ip";
    $login = mysql_result($result,0,"user");
    $password = mysql_result($result,0,"password");
    
    $host = array("url" => $url,
                  "login" => $login,
                  "password" => $password
                 );
    
    return($host);
}


//get Console URL for VM
function getConsole($vmID){
    $vmID = cleanString($vmID);
    include_once("./inc/php/xenapi.php");
    $sql = ("SELECT * FROM `vms` WHERE id=$vmID");
    $result = mysql_query($sql) or die (mysql_error());
    $hostid = mysql_result($result,0,"hostid");
    $uuid = mysql_result($result,0,"uuid");
    
    $sql = ("SELECT * FROM `hosts` WHERE id=$hostid");
    $result = mysql_query($sql) or die (mysql_error());
    
    $ip = mysql_result($result,0,"ip");
    $url = "https://$ip";
    $login = mysql_result($result,0,"user");
    $password = mysql_result($result,0,"password");
    
    $xenserver = new XenApi($url, $login, $password);
    $vm = $xenserver->VM__get_by_uuid($uuid);
    $console = $xenserver->VM__get_consoles($vm);
    
    $record = $xenserver->console__get_record($console['0']);
    
    return($record);
}

function getHostStatus($hostid){
    $hostid = cleanString($hostid);
    include_once("./inc/php/xenapi.php");
    $sql = ("SELECT * FROM `hosts` where id=$hostid");
    $result = mysql_query($sql) or die (mysql_error());
    
    $ip = mysql_result($result,0,"ip");
    $url = "https://$ip";
    $login = mysql_result($result,0,"user");
    $password = mysql_result($result,0,"password");
    $uuid = mysql_result($result,0,"uuid");
    
    $xenserver = new XenApi($url, $login, $password);
    $host = $xenserver->host__get_by_uuid($uuid);
    $vms = $xenserver->VM__get_all();
    $allocatedmem = 0;
    $vmcount = 0;
    foreach ($vms as $vm){
        $vm = $xenserver->VM__get_record($vm);
        if ($vm['is_a_template'] != True AND $vm['is_control_domain'] != True){
            $allocatedmem = $allocatedmem + $vm['memory_target'];
            $vmcount++;
        }
        
    }
    
    $metrics = $xenserver->host__get_metrics($host);
    $metrics = $xenserver->host_metrics__get_record($metrics);
    $hostmem = $metrics['memory_total'];
    $freemem = $hostmem - $allocatedmem;
    $hoststatus = array("vmcount" => $vmcount,
                        "freemem" => $freemem,
                        "hostmem" => $hostmem
                        );
    return ($hoststatus);
    
    
}

function convbytes($input, $output){
    if ($output == "MB"){
        $input = $input / 1048576;
    }
    $input = floor($input);
    return($input);
}

function getUsername($userid){
    $userid = cleanString($userid);
    $sql = ("SELECT * FROM users WHERE id=$userid");
    $result = mysql_query($sql) or die (mysql_error());
    $username = mysql_result($result,"0","user");
    return $username;
}

?>
