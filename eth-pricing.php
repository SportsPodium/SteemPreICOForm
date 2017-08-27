<?php

	function getPrice($url, $table) {
		//$url = 'https://coinmarketcap-nexuist.rhcloud.com/api/eth';

		$buf = json_decode(file_get_contents($url));

		$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

		$server = $url["host"];
		$username = $url["user"];
		$password = $url["pass"];
		$db = substr($url["path"], 1);

		$conn = new mysqli($server, $username, $password, $db);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = 'INSERT INTO `' . $table . '` (`json`, `created_at`) VALUES ("' . addslashes(json_encode($buf[0])) . '", now())';

		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();
	}

	$eth = getPrice('https://api.coinmarketcap.com/v1/ticker/ethereum/', 'eth');
	$steem = getPrice('https://api.coinmarketcap.com/v1/ticker/steem/', 'steem');
	$steemDollar = getPrice('https://api.coinmarketcap.com/v1/ticker/steem-dollars/', 'steemDollar');


