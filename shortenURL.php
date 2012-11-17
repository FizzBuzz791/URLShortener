<?php
	// All the variables that don't change, mostly for the database.
	include('variables.php');

	// This should be whatever your domain is.
	$shortURL = "fizzbuzz.it/";

	if (!isset($_POST['url']))
	{
		die("URL not set");
	}

	$inputURL = $_POST['url']; // Only needed to dump to database.
	
	// Connect here so we can check if the newly generated identifier exist already.
	$mysqli = new mysqli($host, $user, $pass, $db);
	if (mysqli_connect_errno()) { die("Unable to connect to Database!"); }
	$query = "SELECT * FROM Source WHERE shortURL = '$identifier' OR longURL = '$inputURL';";
	$identExists = true;
	$identifier = '';
	while ($identExists)
	{
		$identifier = generateRandomString(10);
		if ($result = $mysqli->query($query)) // Check query worked
		{
			if ($result->num_rows == 0)
			{
				$identExists = false; // Yay! We can use this identifier.
			}
			else // check if it matched a longURL
			{
				$row = $result->fetch_assoc();
				if ($row['longURL'] == $inputURL)
				{
					echo "Your short URL is: " . $shortURL . $row['shortURL'];
					$ip = trim($_SERVER['REMOTE_ADDR']);
					$url = $row['shortURL'];
					$query = "INSERT INTO Stats(URL, userIP) VALUES ('$url', '$ip');";
					if (!$mysqli->query($query)) { die("Problem updating database: " . $mysqli->error); }
					die();
				}
			}
		}
		else
		{
			die("Database problem: " . $mysqli->error);
		}
	}

	$shortURL .= $identifier;

	// This short URL is good. Insert into DB and output to user.
	$query = "INSERT INTO Source(shortURL, longURL) VALUES ('$identifier', '$inputURL');";
	if (!$mysqli->query($query)) { die("Problem updating database: " . $mysqli->error); }

	// Just some quick stats, for fun.
	$ip = trim($_SERVER['REMOTE_ADDR']);
	$query = "INSERT INTO Stats(URL, userIP) VALUES ('$identifier', '$ip');";
	if (!$mysqli->query($query)) { die("Problem updating database: " . $mysqli->error); }

	echo "Your short URL is: " . $shortURL;

	// Generates a random string of length 10 based on the input below.
	function generateRandomString($length)
	{
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
?>
