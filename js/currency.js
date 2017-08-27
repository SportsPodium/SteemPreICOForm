<?php
	include_once(dirname(dirname(__FILE__)) . '/sql.php');

	function getCurrency($table) {
		$conn = connect_mysql();
		$sql = 'SELECT `json` FROM `' . $table . '` ORDER BY `id` DESC LIMIT 1';
		$rs = $conn->query($sql);
		$row = $rs->fetch_object();

		$val = $row->json;
		$conn->close();
		return $val;
	}

	$eth = getCurrency('eth');
	$steem = getCurrency('steem');
	$steemDollar = getCurrency('steemDollar');
?>
	var ethPrice = <?php echo $eth; ?>;
	var steemPrice = <?php echo $steem; ?>;
	var sbdPrice = <?php echo $steemDollar; ?>;

	function steemPerPod(_price) {
		var sd = _price.price_usd;

		var dpp = dollarPerPod();
		var amount = 1;
		var steemCost = (sd / dpp) * amount;

		console.log('steemCost', steemCost);		
		return steemCost;
	}

	function dollarPerPod() {
		var ethDollar = ethPrice.price_usd;
		var steemDollar = steemPrice.price_usd;

		var podsPerEthereum = 2000;
		var dpp = ethDollar / podsPerEthereum;
		return dpp;
	}	

	function calculatePods(amount, currency) {
		var totalCost = steemPerPod((currency == 'STEEM') ? steemPrice : sbdPrice) * amount;
		console.log('totalCost', totalCost, currency);
		return totalCost;
	}

	function calculateBonusPods(amount) {
		return parseFloat(amount) * 0.5;
	}
