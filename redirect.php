<?php
	$delay = "1"; // in seconds

	$url = urldecode($_SERVER["QUERY_STRING"]);
	
	// only allow http/https urls
	/*$pat = '/^http(s)?/i';
	if (!preg_match($pat, $url)) {
		die("Invalid request.");
	}*/

	// preventing XSS
	$url = str_replace("\"", "%22", $url);
	$url = str_replace("'", "%27", $url);

	// truncate for title	
	$title = (strlen($url) > 55) ? substr($url, 0, 51) . "[..]" : $url;
	$title  = htmlentities($title, ENT_QUOTES);
?>
<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="refresh" content="<?=$delay?>; URL='<?=$url?>'" />
		<title>Redirect</title>
	</head>
	<body>

		<div style="background: #DADADA; position: absolute; width: 500px; height: 80px; line-height: 40px; left: 50%; top: 50%; margin-left: -250px; margin-top: -40px; border: 1px dotted #000000; text-align: center;">
			<div style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000">
				You are now leaving DokuWiki.<br>
				<b><i><?=$title?></i></b>
			</div>
		</div>
	</body>
</html>
