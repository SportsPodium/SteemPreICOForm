<?php
	include 'sql.php';
	$conn = connect_mysql();

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
	$values['`sbdPrice`'] = toJson($_POST['sbdPrice']);
	$values['`eth`'] = $_POST['eth'];
	$values['`steem`'] = $_POST['steem'];
	$values['`sbd`'] = $_POST['sbd'];
	$values['`username`'] = toString($_POST['username']);
	$values['`target`'] = toString($_POST['target']);
	$values['`amount`'] = toString($_POST['amount']);
	$values['`memo`'] = toString($_POST['memo']);
	$values['`pods`'] = $_POST['pods'];
	$values['`dollarPerPod`'] = $_POST['dollarPerPod'];

	$sql = 'INSERT INTO purchases (' . implode(',', array_keys($values)) . ') VALUES (' . implode(',', array_values($values)) . ')';

	if ($conn->query($sql) === TRUE) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$conn->close();
