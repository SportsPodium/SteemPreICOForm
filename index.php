<?php
	function getSteemitUsername() {
		if (isset($_ENV['steemit_username'])) return $_ENV['steemit_username'];
		return null;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>
		SportsPodium Pre-ICO
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
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
</head>
<body>
	<div class="container">
		<h1><img src="./img/sportspodium_logo.png" style="width: 150px; margin-bottom: 20;" /></h1>
		<h1>Contribute to the Pre-ICO for SportsPodium</h1>
		<?php include 'form.php'; ?>
	</div>
</body>
</html>