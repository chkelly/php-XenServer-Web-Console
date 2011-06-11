#!/usr/bin/env python
import MySQLdb
import sys, time
import subprocess
try:
    import XenAPI
except ImportError:
    print "Failed to import XenAPI"
    sys.exit(1)
    
###########Database Connection Information##############
#Define Connection information for querying the DB.
hostname = "localhost"
username = ""
password = ""
database = ""
########################################################

#Connect to database and get templateid and hostid
try:
    db = MySQLdb.connect(hostname,username,password,database)
    cursor = db.cursor()
except:
    print "Unable to Connect to Database Server"
    sys.exit(1)

cursor.execute("SELECT * FROM vms WHERE progress='-100'")
numrows = int(cursor.rowcount)

if numrows == 0:
    print "Nothing for me to do..."
    sys.exit(1)

result = cursor.fetchone()

vmid = result[0]
hostid = result[2]
uuid = result[3]

cursor.execute("SELECT * from hosts where id="+str(hostid))
numrows = int(cursor.rowcount)
result = cursor.fetchone()

host_ip = result[1]
host_url = "https://"+str(result[1])
host_user = result[6]
host_pass = result[7]

#Create a session on the xenserver
session = XenAPI.Session(host_url)
try:
    session.xenapi.login_with_password(host_user,host_pass)
except:
    print "Unable to connect to XenServer."
    
#Get the VM object from the UUID
vm = session.xenapi.VM.get_by_uuid(uuid)

#Hard Shutdown and remove the VM the VM.

def remove(vm):
    try:
        vmrecord = session.xenapi.VM.get_record(vm)
        if vmrecord['power_state'] != "Halted":
            session.xenapi.VM.hard_shutdown(vm)
        session.xenapi.VM.destroy(vm)
        return "Removed"
        
    except:
        return None
    
while remove(vm) == None: time.sleep(1)

#cleanup time....

cursor.execute("UPDATE ips SET vmid=0 WHERE vmid='"+str(vmid)+"'")
cursor.execute("DELETE from vms WHERE id='"+str(vmid)+"'")
