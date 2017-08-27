<?php
	include 'sql.php';

	$maxDollars = 500000;

	$conn = connect_mysql();
		
	$sql = 'SELECT SUM(dollarPerPod * pods) AS total_dollars FROM `purchases`';

	$rs = $conn->query($sql);

	$row = $rs->fetch_object();

	$totalDollars = round($row->total_dollars, 2);
	$totalDollars += $_POST['amount'];

	$conn->close();

	if ($totalDollars > $maxDollars) {
		echo json_encode(['success' => false]);
		exit;
	}
	echo json_encode(['success' => true]);
