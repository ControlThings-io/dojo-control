<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" /> 
</head>
<body>
Setting up the DBs.

<?php
include 'config.inc';

$mysqli = new mysqli($dbhost, $dbuser, $dbpass) or die('Error connecting to mysql');
$mysqli->query("DROP DATABASE IF EXISTS $dbname");
echo $mysqli->error;
$mysqli->query("CREATE DATABASE $dbname");
echo $mysqli->error;

include 'opendb.inc';
$query = 'CREATE TABLE blogs_table( '.
		'cid INT NOT NULL AUTO_INCREMENT, '.
         	'blogger_name TEXT, '.
         	'comment TEXT, '.
		'date DATETIME, '.
		'PRIMARY KEY(cid))';	
$result = $mysqli->query($query);
#echo $mysqli->error($conn );

$query = 'CREATE TABLE accounts( '.
		'cid INT NOT NULL AUTO_INCREMENT, '.
         	'username TEXT, '.
         	'password TEXT, '.
		'contact TEXT, '.
		'PRIMARY KEY(cid))';
$result = $mysqli->query($query);
//echo $mysqli->error($conn );

$query = 'CREATE TABLE hitlog( '.
		'cid INT NOT NULL AUTO_INCREMENT, '.
         	'hostname TEXT, '.
         	'ip TEXT, '.
		'browser TEXT, '.
		'action TEXT, '.
		'date DATETIME, '.
		'PRIMARY KEY(cid))';		 
$result = $mysqli->query($query);
//echo $mysqli->error($conn );

$query = "INSERT INTO accounts (username, password, contact) VALUES
	('admin', 'Flynn', '1-800-HLP-DESK<br>Some private island'),
	('adrian', 'somepassword', '1-713-999-9999<br>000 Zombie Lane<br>Solar Power City, TX, USA'),
	('john', 'monkey', '+91 +91-11-24364050<br>123 Rain Forest Road<br>Monkeyville, Goa, India'),
	('ed', 'pentest', 'Commandline KungFu anyone?')";
//echo $query;
$result = $mysqli->query($query);
//echo $mysqli->error($conn );

$query ="INSERT INTO `blogs_table` (`cid`, `blogger_name`, `comment`, `date`) VALUES
	(1, 'adrian', 'Well, been working on this for a bit. Welcome to my crappy Shift Logger software. :)', '2018-01-01 22:26:12'),
	(2, 'adrian', 'Looks like I got a lot more work to do. Fun, Fun, Fun!!!', '2018-01-01 22:26:54'),
	(3, 'anonymous', 'An anonymous shift entry? Huh?', '2018-01-01 22:27:11'),
	(4, 'ed', 'I'm out of here.  Off shift.  Nothing to report.', '2018-01-01 22:27:48'),
	(5, 'john', 'Why did the IT guys take down our torrent server!', '2018-01-01 22:29:04'),
	(6, 'john', 'Why give users the ability to get to the unfiltered Internet? It is just asking for trouble in a control network. ', '2018-01-01 22:29:49'),
	(7, 'john', 'Nextflix is GOOD!!!', '2018-01-01 22:30:06'),
	(8, 'admin', 'Fear me, for I am ROOT!', '2018-01-01 22:31:13')";
//echo $query;
$result = $mysqli->query($query);
//echo $mysqli->error($conn );

exec("cp snake/highscores.txt.bak snake/highscores.txt");
echo "<p>If you see no errors above, it should be done. <a href=\"index.php\">Continue back to the frontpage.</a>";
?>
</body>
</html>
