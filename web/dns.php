<center><h2><b>DNS Lookup</b></h2></center><p>

<?php
echo "<form method=\"POST\" action=\"" .$_SERVER['SCRIPT_NAME'] . "?" . $_SERVER['QUERY_STRING'] . "\">";
?>

	<p>Type the hostname of an asset to find its IP address:</p>
	<p><input type="text" name="target_host" size="20"></p>
	<p><input type="submit" value="Submit"></p>
	
</form>
<?php
// Grab inputs
$targethost = @$_REQUEST["target_host"];

echo "<pre>";
echo shell_exec("host -W 1 " . $targethost);
echo "</pre>";
//phpinfo();
?>
<?php
// Begin hints section
if (@$_COOKIE["showhints"]==1) {
	echo '<p><div style="background-color: #FFFF00"></p> <p><center><h2><b>HINT - Disable with link on the left</b></h2></center></p>
	<b>For Command Injection Flaws:</b> Directly building a command to use in a
	shell? Bad idea! Check out command separators like ; and && depending on if 
	you are using Linux or Windows respectively.
	</div>'; 
}
// End hints section
?>
