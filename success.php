<?php
	include 'sql.php';

	$maxDollars = 500000;

	$conn = connect_mysql();
		
	$sql = 'SELECT SUM(dollarPerPod * pods) AS total_dollars FROM `purchases`';

	$rs = $conn->query($sql);
	$row = $rs->fetch_object();

	$totalDollars = round($row->total_dollars, 2);
	$percentMaxDollars = round($totalDollars / $maxDollars * 100);

	$conn->close();

	$conn = connect_mysql();
	$sql = 'SELECT SUM(pods) AS total_pods FROM `purchases` WHERE username = "' . addslashes($_GET['username']) . '"';

	$rs = $conn->query($sql);
	$row = $rs->fetch_object();

	$totalPods = $row->total_pods;
?>

<html>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="./bootstrap-3.3.7-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="./bootstrap-3.3.7-dist/css/bootstrap-theme.min.css">

<script src="./js/jquery-3.2.1.min.js"></script>
<script src="./bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<script src="./js/steem.min.js"></script>
<style type="text/css">
	h1 {
		text-align: center;
	}
	.form-horizontal {
		max-width: 800px;
		margin: 0 auto;
	}
</style>
<body>
	<div class="container">
		<h1><img src="./img/sportspodium_logo.png" style="width: 150px; margin-bottom: 20;" /></h1>
		<form class="form-horizontal">
			<div class="form-group">
				<label for="username" class="col-sm-4 control-label">total dollars</label>
				<div class="col-sm-6">
					<p class="form-control-static">US$ <?php echo number_format($totalDollars, 2); ?> / US$ <?php echo number_format($maxDollars, 2); ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="username" class="col-sm-4 control-label">percent sold</label>
				<div class="col-sm-6" style="margin-top:5px">
					<div class="progress">
						<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $percentMaxDollars; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentMaxDollars; ?>%">
							<span class="sr-only"><?php echo $percentMaxDollars; ?>% Complete</span>
						</div>
					</div>

				</div>
			</div>	
		</form>	
		<h1 style="text-align: center;">Thank you for reserving your PODS</h1>
		<p style="text-align: center;">You have purchased a total of <b><?php echo number_format($totalPods, 3); ?> PODS</b></p>
	</div>
</body>
</html>	