<html>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="./bootstrap-3.3.7-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="./bootstrap-3.3.7-dist/css/bootstrap-theme.min.css">

<script src="./js/jquery-3.2.1.min.js"></script>
<script src="./bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<script src="./js/steem.min.js"></script>
<script src="./js/app.js"></script>
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
		<h1><img src="./img/sportspodium_logo.png" style="width: 300px;" /></h1>
		<h1>Contribute to the Pre-ICO for SportsPodium</h1>
		<?php include 'form.php'; ?>
	</div>
</body>
<script>
$(function() {
	steem.api.getAccounts(['ned', 'dan'], function(err, result) {
		console.log(err, result);
	});
});
</script>
</html>