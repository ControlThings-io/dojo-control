<?php
include 'config.inc';
include 'opendb.inc';

// Grab inputs
$username = @@$_REQUEST["user_name"];
$password = @@$_REQUEST["password"];
$dosomething = @@$_REQUEST["do"];

if ($username <> "" and $password <> "") {
	$query  = "SELECT * FROM accounts WHERE username='". $username ."' AND password='".stripslashes($password)."'";
	$result = $mysqli->query($query) or die($mysqli->error . '<p><b>SQL Statement:</b>' . $query);
	if ($result->num_rows > 0) {
		// flag the cookie as secure only if it is accessed via SSL
		$ssl = FALSE;
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
			// SSL connection
			$ssl = TRUE;
		}
		// set a random md5 as the session value
                $rndm = rand();
                $value = md5($rndm);
                setcookie("sessionid", $value, 0, "/", "", $ssl, TRUE);
		// set uid to appropriate user
		$row = $result->fetch_assoc();
		setcookie("uid", base64_encode($row['cid']), 0, "/", "", $ssl, FALSE); 
		$failedloginflag=0;
		echo '<meta http-equiv="refresh" content="0;url=index.php">';
		$action = "Successful login for " . $username;

	} else {
		$failedloginflag=1;
		$action = "Failed login for " . $username;
	}
	// Log attempt to DB
	$query = "INSERT INTO hitlog(hostname, ip, browser, action, date) VALUES ('".
        		gethostbyaddr($_SERVER['REMOTE_ADDR']) . "', '".
        		@$_SERVER['REMOTE_ADDR'] . "', '".
        		@$_SERVER['HTTP_USER_AGENT'] . "', '".
        		$action . "', ".
        		" now() )";
	$result = $mysqli->query($query) or die($mysqli->error);
} else {
	$failedloginflag=0;
}

switch ($dosomething) {
	case "logout":
		setcookie('sessionid','',1);
		setcookie('uid','',1);
		break;
	case "togglehints":
		if (@@$_COOKIE["showhints"] == 0) {
		setcookie('showhints','1');
		} else {
		setcookie('showhints','0');
		}		
		break;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
<?php
if ($dosomething  == "logout") {
	echo '<meta http-equiv="refresh" content="0;url=index.php">';
}
?>
  <meta content="text/html; charset=us-ascii" http-equiv="content-type">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" /> 
</head>
<body>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr><td bgcolor="lightblue" align="center" colspan="2">
		<div align="center"><h1><b>Welcome to Dojo Control's Shift Logger!</b></h1>
		<?php
		?>
		<!-- Old username query for welcome message before move to base64 uids
		$query  = "SELECT * FROM accounts WHERE cid='".@@$_COOKIE["uid"]."'";
		-->
		<?php
		$query  = "SELECT * FROM accounts WHERE cid='".base64_decode(@@$_COOKIE["uid"])."'";
		$result = $mysqli->query($query) or die($mysqli->error . '<p><b>SQL Statement:</b>' . $query);
		echo $mysqli->error;
		echo $mysqli->error;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc())
			{
				$logged_in_user = $row['username'];
				echo '<font color="#0000ff"><h2>You are logged in as ' . $logged_in_user . '</h2></font>';
			}
		} else {
			$logged_in_user = "anonymous";
			echo '<font color="#ff0000">Not logged in</font>';
		}
		?>
		</div>
	</td></tr>
	<tr>
		<td bgcolor="lightblue" valign="top" width="200">
		<hr>
		<b>Main Menu</a></b><br>
		<hr>
		<a href="index.php">Home</a><br>
<?php if (@@$_COOKIE["sessionid"]) { ?>		
		<a href="?page=user-info.php">User Info</a><br>
		<a href="?page=enter-shift-log.php">Enter Shift Log</a><br>
		<a href="?page=view-shift-logs.php">View Shift Logs</a><br>
		<a href="?page=text-file-viewer.php">Reading Room</a><br>
		<a href="snake/">Play Snake</a><br>
<?php } ?>
<?php if (!@@$_COOKIE["sessionid"]) { ?>		
		<a href="?page=register.php">Register</a><br>
		<a href="?page=login.php">Login</a><br>
<?php } ?>
<?php if (@@$_COOKIE["sessionid"]) { ?>		
		<a href="?do=logout">Logout</a><br>
		<br>
		<hr>
		<b>User Utilities</a></b><br>
		<hr>
		<a href="?page=ping.php">Ping Asset</a><br>
		<a href="?page=dns.php">DNS Lookup</a><br>
		<a href="?page=browser-info.php">Browser Info</a><br>
<?php } ?>
		<!--<a href="?page=debugger.php">Debugger</a></br>-->
		<br>
<!-- Check to see if admin is logged in and only show admin tool section if true --> 
<?php if (base64_decode(@@$_COOKIE["uid"])==1) { ?>		
		<hr>
		<b>Admin Controls</a></b><br>
		<hr>
		<a href="?page=login-history.php">Login History</a><br>
		<a href="?page=source-viewer.php">Source Viewer</a><br>
		<br>
<?php } ?>

<!-- Diferent behavior for diferent UAs -->
<?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'],"Googlebot") !== FALSE)) { ?>
		<hr>
		<b>Search Engines</a></b><br>
		<hr>
		Keywords: pentest, ics<br>
		<br>
<?php } ?>
<?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone") !== FALSE)) { ?>
		<hr>
		<b>Mobile: iPhone</a></b><br>
		<hr>
		Running iOS 6?<br>
		Print maps here :)<br>
		<br>
<?php } ?>
<?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'],"Android") !== FALSE)) { ?>
		<hr>
		<b>Mobile: Android</a></b><br>
		<hr>
		Running Android?<br>
		Update to 4.x now<br>
		<br>
<?php } ?>
<?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 3.02") !== FALSE)) { ?>
		<hr>
		<b>Old browser</a></b><br>
		<hr>
		<a href="/passwords/">Contact the admin</a><br>
		<br>
<?php } ?>

		<hr>
		<b>Pentester Help</a></b><br>
		<hr>
		<script>
		function do_open(href) {
			if (window.confirm('You are invoking functionality that is outside the scope of the penetration test and may contaminate the results of your spidering and scanning tools. Please disable your interception proxy before continuing.')) { window.location.replace('index.php'+href); }
		}
		</script>
		<a href="#" onclick="do_open(String.fromCharCode(63)+'page'+String.fromCharCode(61)+'about'+String.fromCharCode(46)+'php');">About</a><br>
		<a href="#" onclick="do_open(String.fromCharCode(63)+'do'+String.fromCharCode(61)+'togglehints');">Toggle Hints</a><br>
		<a href="#" onclick="do_open(String.fromCharCode(63)+'page'+String.fromCharCode(61)+'vuln-list'+String.fromCharCode(46)+'php');">Vuln List</a><br>
		<a href="#" onclick="do_open(String.fromCharCode(63)+'page'+String.fromCharCode(61)+'credits'+String.fromCharCode(46)+'php');">Credits</a><br>		
		<a href="#" onclick="do_open(String.fromCharCode(63)+'page'+String.fromCharCode(61)+'reset-db'+String.fromCharCode(46)+'php');">Reset DB</a>
		</td>
		<td  valign="top">
		<blockquote>
		<!-- Begin Content -->
