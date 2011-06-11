<div id='bodyContent'>
    <form action='provision.php' method='post'>
        <table>
        <tr><td><label for='name'>VM Name</label></td></tr>
        <tr><td><input type='text' name='name'></td></tr>
        
        <tr><td><label for='memory'>Memory</label></td></tr>
        <tr><td><select name='memory'>
                <option value='512'>512 MB</option>
        </td></tr>
        
        <tr><td><label for='location'>Location</label></td></tr>
        <tr><td><select name='location'>
                <option>DCA3</option>
                <option>DCA2</option>
        </td></tr>
        
        <tr><td><label for='hosts'>Host Server</label></td></tr>
        <tr><td id='hosts'><select name='host'>
                <option value='1'>VPS1.dca3</option>
                <option value='2'>VPS1.dca2</option>
        </td></tr>
        
        <tr><td><label for='os'>OS</label></td></tr>
        <tr><td id='os'><select name='os'>
                <option value=1>CentOS 5.5 32Bit</option>
                <option value=1>Windows 2003 Standard x86</option>
        </td></tr>
        
        <tr><td><input type='submit' value='Provision'></td></tr>
        </table>
    </form>
</div>
