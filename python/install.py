#!/usr/bin/env python
import MySQLdb
import sys, time
import subprocess
try:
    import XenAPI, provision
except:
    print "fail"

###########Database Connection Information##############
#Define Connection information for querying the DB.
hostname = ""
username = ""
password = ""
database = ""
########################################################

#Connect to database and get templateid and hostid
db = MySQLdb.connect(hostname,username,password,database)
cursor = db.cursor()
cursor.execute("SELECT * FROM vms WHERE progress=0")
numrows = int(cursor.rowcount)

if numrows == 0:
    print "Nothing for me to do..."
    sys.exit(1)

result = cursor.fetchone()

vmid = result[0]
userid = result[1]
hostid = result[2]
vmname = result[6]
templateid = result[8]

#Change the progress to 10% to indicate the script is working on it.
cursor.execute("UPDATE vms SET progress='10' WHERE id='"+str(vmid)+"'")
#Use the hostid to get the connection information for the server.
db = MySQLdb.connect(hostname,username,password,database)
cursor = db.cursor()
cursor.execute("SELECT * from hosts where id="+str(hostid))
numrows = int(cursor.rowcount)
result = cursor.fetchone()

host_ip = result[1]
host_url = "https://"+str(result[1])
host_user = result[6]
host_pass = result[7]

host_mask = result[15]
host_gw = result[16]
host_dns1 = result[17]
host_dns2 = result[18]

#Fetch the assigned IP.
cursor.execute("SELECT ip from ips where vmid='"+str(vmid)+"'")
result = cursor.fetchone()
vm_ip = result[0]

#Create a session on the xenserver
session = XenAPI.Session(host_url)
try:
    session.xenapi.login_with_password(host_user,host_pass)
except:
    print "Unable to connect to XenServer."

cursor.execute("UPDATE vms SET progress='20' WHERE id='"+str(vmid)+"'")

# Choose the PIF with the alphabetically lowest device
try:
    pifs = session.xenapi.PIF.get_all_records()
    for pif in pifs:
        device = pifs[pif]
        if device['IP'] == host_ip:
            network = session.xenapi.PIF.get_network(pif)
    print "Chosen PIF is connected to network: ", session.xenapi.network.get_name_label(network)
except:
    print "Error choosing PIF"

cursor.execute("UPDATE vms SET progress='30' WHERE id='"+str(vmid)+"'")

# List all the VM objects
vms = session.xenapi.VM.get_all_records()
print "Server has %d VM objects (this includes templates):" % (len(vms))

templates = []
for vm in vms:
    record = vms[vm]
    ty = "VM"
    if record["is_a_template"]:
        ty = "Template"
        # Look for a template matching the given template ID
        if record["name_label"].startswith("T-"+str(templateid)):
            templates.append(vm)
            print "  Found %8s with name_label = %s" % (ty, record["name_label"])
    
cursor.execute("UPDATE vms SET progress='40' WHERE id='"+str(vmid)+"'")

print "Choosing a template to clone"
if templates == []:
    print "Could not find a template. Exitting"
    sys.exit(1)

cursor.execute("UPDATE vms SET progress='50' WHERE id='"+str(vmid)+"'")
    
template = templates[0]
print "  Selected template: ", session.xenapi.VM.get_name_label(template)
print "Installing new VM from the template"
vm = session.xenapi.VM.clone(template, vmname)
print "  New VM has name: "+vmname
print "Creating VIF"
vif = { 'device': '0',
        'network': network,
        'VM': vm,
        'MAC': "",
        'MTU': "1500",
        "qos_algorithm_type": "",
        "qos_algorithm_params": {},
        "other_config": {} }
vif = session.xenapi.VIF.create(vif)
print "Adding noniteractive to the kernel commandline"
session.xenapi.VM.set_PV_args(vm, "noninteractive")
session.xenapi.VM.provision(vm)

cursor.execute("UPDATE vms SET progress='60' WHERE id='"+str(vmid)+"'")

#Get the MAC address of the new interface
vm_mac = session.xenapi.VIF.get_MAC(vif)

cursor.execute("UPDATE vms SET progress='70' WHERE id='"+str(vmid)+"'")

#SSH to the xenserver and append it to the dhcpd.conf file and restart the server.
sshConnect=host_user+"@"+host_ip
proc = subprocess.Popen(['ssh', sshConnect, 'echo "host '+str(vmid)+'{" >> /etc/dhcpd.conf;',
                         'echo "hardware ethernet '+vm_mac+';" >> /etc/dhcpd.conf;',
                         'echo "option routers '+host_gw+';" >> /etc/dhcpd.conf;',
                         'echo "option subnet-mask '+host_mask+';" >> /etc/dhcpd.conf;',
                         'echo "fixed-address '+vm_ip+';" >> /etc/dhcpd.conf;',
                         'echo "option domain-name-servers '+host_dns1+','+host_dns2+';" >> /etc/dhcpd.conf;',
                         'echo "}" >> /etc/dhcpd.conf;',
                         'service dhcpd restart',                         
                         ],stdin=subprocess.PIPE)

print "Starting VM"
session.xenapi.VM.start(vm, False, True)

cursor.execute("UPDATE vms SET progress='80' WHERE id='"+str(vmid)+"'")

print "  VM is booting"
print "Waiting for the installation to complete"
# Here we poll because we don't generate events for metrics objects currently
def read_ip_address(vm):
    vgm = session.xenapi.VM.get_guest_metrics(vm)
    try:
        os = session.xenapi.VM_guest_metrics.get_networks(vgm)
        if "0/ip" in os.keys():
            return os["0/ip"]
            cursor.execute("UPDATE vms SET progress='90' WHERE id='"+str(vmid)+"'")
        return None
    except:
        return None
while read_ip_address(vm) == None: time.sleep(1)
print "Reported IP: ", read_ip_address(vm)

vm_uuid = session.xenapi.VM.get_uuid(vm)

print "UUID="+vm_uuid
cursor.execute("UPDATE vms set uuid='"+vm_uuid+"' WHERE id='"+str(vmid)+"'")
session.xenapi.session.logout()
cursor.execute("UPDATE vms SET progress='100' WHERE id='"+str(vmid)+"'")
