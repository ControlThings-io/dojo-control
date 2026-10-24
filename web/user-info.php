<center><h2><b>View your details</b></h2></center><p>
If you do not have an account, <a href="?page=register.php">Register</a><p>
Enter your username an password below to view your infromation:
<?php
echo "<form method=\"POST\" action=\"" .$_SERVER['SCRIPT_NAME'] . "?" . $_SERVER['QUERY_STRING'] . "\">";
?>
	<p>Name:<br><input type="text" name="view_user_name" size="20"></p>
	<p>Password:<br><input type="password" name="password" size="20"></p>
	<p><input type="submit" value="Submit"></p>
	
</form>

<?php
// Grab inputs
$viewusername = @$_REQUEST["view_user_name"];
$password = @$_REQUEST["password"];

// The below is just test code. The real login happens at the head of header.php.
if ($viewusername <> "") {
	$query  = "SELECT * FROM accounts WHERE username='". $viewusername ."' AND password='".stripslashes($password)."'";
	//echo $query;		
	$result = $mysqli->query($query) or die($mysqli->error . '<p><b>SQL Statement:</b>' . $query);
	echo '<p>Results:<p>';
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc())
		{
		echo "<b>Username=</b>{$row['username']}<br>";
		echo "<b>Password=</b>{$row['password']}<br>";
		echo "<b>Contact Info=</b><pre>{$row['contact']}</pre><br><p>";
		}
		echo "<p>";
	} else {
		echo '<font color="#ff0000">Bad user name or password</font>';
	}
} 
//phpinfo();
?>
<?php
// Begin hints section
if (@$_COOKIE["showhints"]==1) {
	echo '<p><div style="background-color: #FFFF00"></p> <p><center><h2><b>HINT - Disable with link on the left</b></h2></center></p>
	<b>For SQL Injection:</b>The old "\' or 1=1 -- " is a classic, but there are others. This just shows
	you too much info. Also try an injection like this on the <a href="?page=login.php">Login</a> page.
	<br><br>
	<b>For Session and Authentication:</b>As for playing with sessions, try a 
	<a href="https://addons.mozilla.org/en-US/firefox/addon/4510">cookie editor</a> 
	to change your UID.
	<br><br>
	<b>For XSS:</b> You may also see an XSS attack you left when you used the
	registration page.
	</div>'; 
}
// End hints section
?>

