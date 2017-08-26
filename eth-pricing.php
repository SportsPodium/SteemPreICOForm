<?php
	$url = 'https://coinmarketcap-nexuist.rhcloud.com/api/eth';

	$buf = json_decode(file_get_contents($url));

	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

	$server = $url["host"];
	$username = $url["user"];
	$password = $url["pass"];
	$db = substr($url["path"], 1);

	$conn = new mysqli($server, $username, $password, $db);

	$sql = 'INSERT INTO pricing (`buffer`, `created_at`) VALUES ("' . json_encode($buf) . '", now())';

	$conn->query($sql);

	$conn->close();
