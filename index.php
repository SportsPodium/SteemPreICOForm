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
	<script>
		window.console = {
			log: function() {},
			error: function() {},
			info: function() {}
		};
	</script>
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
		<a href="https://github.com/SportsPodium/SteemPreICOForm" target="_blank"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/365986a132ccd6a44c23a9169022c0b5c890c387/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f7265645f6161303030302e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png"></a>
		<h1><img src="./img/sportspodium_logo.png" style="width: 150px; margin-bottom: 20;" /></h1>
		<h1>Contribute to the Pre-ICO for SportsPodium</h1>
		<?php include 'form.php'; ?>
	</div>
</body>
</html>