<?php
	include('variables.php');
	$expectedURL = trim($_SERVER['REQUEST_URI']);
		echo "ExpectedURL: $expectedURL \n";
	$split = preg_split("{\/}",$expectedURL);
	$shortURL = $split[1];
		echo "shortURL: $shortURL \n";
	// security: strip all but alphanumerics & dashes
	$shortURL = preg_replace("/[^a-z0-9-]+/i", "", $shortURL);

	$isShortURL = false;
	$result = getLongURL($shortURL);
	if ($result)
	{
		$isShortURL = true;
	
		// add some tracking info to database.
		$mysqli = new mysqli($host, $user, $pass, $db);
	
		if (mysqli_connect_errno())
		{
			die("Unable to connect: " . $mysqli->error);
		}

		$ip = trim($_SERVER['REMOTE_ADDR']);
		$query = "INSERT INTO Stats (URL, userIP) VALUES ('$shortURL', '$ip');";
	
		if (!$mysqli->query($query))
		{
			die("Issue updating Stats: " . $mysqli->error);
		}	
	}
	$longURL = $result['longURL'];

	if ($isShortURL)
	{
		redirectTo($longURL, $shortURL);
	}
	else
	{
		show404(); // no a valid shortURL, display basic 404
	}

	function getLongURL($s)
	{
		include('variables.php');
		$mysqli = new mysqli($host, $user, $pass, $db);
		
		if (mysqli_connect_errno())
		{
			die("Unable to connect!" . $mysqli->error . "Error no: " . $mysqli->errno);
		}

		$query = "SELECT * FROM Source WHERE shortURL = '$s';";

		if ($result = $mysqli->query($query))
		{
			if ($result->num_rows > 0)
			{
				while ($row = $result->fetch_assoc())
				{
					return($row);
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}

		$mysqli->close();
	}

	function redirectTo($longURL)
	{
		header("Referer: http://fizzbuzz.it");
		header("Location: $longURL", TRUE, 301);
		exit;
	}

	function show404()
	{
		echo "404 Page Not Found.";
		exit;
	}
?>
