@echo off

rem   This is an example of running TightVNC Java viewer 1.5 using automatic
rem   SSH tunneling.  This example connects to 192.168.0.2 via SSH and then
rem   connects from that SSH host to localhost with TightVNC (port 5901).
rem   Note that all traffic from the viewer to 192.168.0.2 will be encrypted
rem   with SSH.

java -jar TightVncViewer.jar SOCKETFACTORY com.tightvnc.vncviewer.SshTunneledSocketFactory SSHHOST 192.168.0.2 HOST localhost PORT 5901
