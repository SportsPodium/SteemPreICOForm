<?php
	include 'sql.php';
	$conn = connect_mysql();

	function slack($message) {
        $data = "payload=" . json_encode(array(
                "text" =>  $message,
            ));
	
	// You can get your webhook endpoint from your Slack settings
        $ch = curl_init(getenv('SLACK_WEBHOOK_URL'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

	// Laravel-specific log writing method
        // Log::info("Sent to Slack: " . $message, array('context' => 'Notifications'));
        return $result;
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
	$values['`sbdPrice`'] = toJson($_POST['sbdPrice']);
	$values['`eth`'] = $_POST['eth'];
	$values['`steem`'] = $_POST['steem'];
	$values['`sbd`'] = $_POST['sbd'];
	$values['`username`'] = toString($_POST['username']);
	$values['`target`'] = toString($_POST['target']);
	$values['`amount`'] = toString($_POST['amount']);
	$values['`memo`'] = toString($_POST['memo']);
	$values['`email`'] = toString($_POST['email']);
	$values['`pods`'] = $_POST['pods'];
	$values['`podsBonus`'] = $_POST['podsBonus'];
	$values['`podsTotal`'] = $_POST['podsTotal'];
	$values['`dollarPerPod`'] = $_POST['dollarPerPod'];

	$memo = $_POST['memo'];

	$sql = 'INSERT INTO purchases (' . implode(',', array_keys($values)) . ') VALUES (' . implode(',', array_values($values)) . ')';

	if ($conn->query($sql) === TRUE) {
		echo "New record created successfully";
		slack("A new `POD` sale just took place :champagne:!!\n`$memo`");
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$conn->close();
