<?php
	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

	$server = $url["host"];
	$username = $url["user"];
	$password = $url["pass"];
	$db = substr($url["path"], 1);

	$conn = new mysqli($server, $username, $password, $db);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	function toJson($s) {
		return '"' . addslashes(json_encode($s)) . '"';
	}

	function toString($s) {
		return '"' . addslashes($s) . '"';
	}

	$values = [];
	$values['`created_at`'] = 'now()';
	$values['`ethPrice`'] = toJson($_POST['ethPrice']);
	$values['`steemPrice`'] = toJson($_POST['steemPrice']);
	$values['`eth`'] = $_POST['eth'];
	$values['`steem`'] = $_POST['steem'];
	$values['`username`'] = toString($_POST['username']);
	$values['`target`'] = toString($_POST['target']);
	$values['`amount`'] = toString($_POST['amount']);
	$values['`memo`'] = toString($_POST['memo']);
	$values['`pods`'] = $_POST['pods'];
	$values['`dollarPerPod`'] = $_POST['dollarPerPod'];
	print_r($values);

	$sql = 'INSERT INTO purchases (' . implode(',', array_keys($values)) . ') VALUES (' . implode(',', array_values($values)) . ')';

	if ($conn->query($sql) === TRUE) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$conn->close();
