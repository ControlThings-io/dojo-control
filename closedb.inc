<?php
//$query = "INSERT INTO hitlog(hostname, ip, browser, action, date) VALUES ('".
//	gethostbyaddr($_SERVER['REMOTE_ADDR']) . "', '".
//	@$_SERVER['REMOTE_ADDR'] . "', '".
//	@$_SERVER['HTTP_USER_AGENT'] . "', '".
//	@$_SERVER['HTTP_REFERER'] . "', ".
//	" now() )";
//$result = $mysqli->query($query) or die($mysqli->error);

mysqli_close($mysqli);
?>
