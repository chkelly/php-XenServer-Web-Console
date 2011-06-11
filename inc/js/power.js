function power(action,id){
    trid = "status" + id;
    trcontrol = "control" + id;
    
    var statuscell = document.getElementById(trid);
    var controlcell = document.getElementById(trcontrol);
    statuscell.innerHTML = "<img src='./inc/images/powerload.gif'>";
    if (action==""){
      document.getElementById(trid).innerHTML="";
      return;
    } 
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    }
    else{// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function(){
      if (xmlhttp.readyState==4 && xmlhttp.status==200){
        document.getElementById(trid).innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET","./inc/php/power.php?action="+action+"&id="+id,true);
    xmlhttp.send();
    
    if (action == "shutdown"){
        controlcell.innerHTML = "<a href='#' onClick=power('start','"+id+"');><img src='./inc/images/on.png' alt='Power On' title='Power On'></a>";
    }
    if (action == "start"){
        controlcell.innerHTML = "<a href='#' onClick=power('reboot','"+id+"');><img src='./inc/images/reboot.png' alt='Reboot' title='reboot'></a><a href='#' onClick=power('shutdown','"+id+"');><img src='./inc/images/off.png' alt='Shutdown' title='Shutdown'></a>";
    }
}