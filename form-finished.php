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

	if ($totalDollars > $maxDollars) {
		header('location: done.php');
		exit;
	}
?>
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

<div class="well">
	<p>This website makes it as easy as possible for you to buy PODs with Steem or SBD, but requires you to enter your Steem password on the next page. We do not store your password and only use it to authorize this one transaction. You are welcome to have a look into our code on GitHub <a href="https://github.com/SportsPodium/SteemPreICOForm" target="_blank">https://github.com/SportsPodium/SteemPreICOForm</a></p>

	<p>But if you have any doubt and would still like to get your hands on some PODs, please do a direct transfer from your Steem Wallet to the <b>@sportspodium</b> Steem wallet and just specify your email address in the memo field. We will pick up the transaction from the Steem blockchain and issue your PODs based on exchange prices at the transaction time.</p>
</div>

<form class="form-horizontal" id="authentication-form">
	<div class="form-group" id="username-container">
		<label for="username" class="col-sm-4 control-label">steemit username</label>
		<div class="col-sm-6">
			<div class="input-group">
				<span class="input-group-addon">@</span>
				<input type="text" class="form-control" id="username" required type="text" autocorrect="off" autocapitalize="none" placeholder="steemit username">
			</div>
		</div>
	</div>
	<div class="form-group" id="lookup-button-container">
		<div class="col-sm-offset-4 col-sm-6">
			<button id="lookup-pods" class="btn btn-success">See POD Allocation</button>
		</div>
	</div>
	<div id="alert-lookup-success" class="form-group" style="display: none;">
		<div class="col-sm-12">
			<div class="alert alert-success">Account Found</div>
		</div>
	</div>
	<div id="alert-lookup-error" class="form-group" style="display: none;">
		<div class="col-sm-12">
			<div class="alert alert-danger">Account lookup Failed</div>
		</div>
	</div>
</form>

<script>
	$(function() {
		<?php include './js/app.js'; ?>
		<?php include './js/currency.js'; ?>

		var $transferDescription = $('#transfer-description');
		var $amount = $('#amount');
		
		var $steemPrice = $('#steem-price');
		var $ethereumPrice = $('#eth-price');
		var $steemDollarPrice = $('#steem-dollar-price');

		var $username = $('#username');
		var $email = $('#email');
		var $password = $('#password');
		var $amount = $('#amount');
		var $pods = $('#pods');
		var $podsTotal = $('#total-pods');
		var $podsBonus = $('#bonus-pods');
		var $memo = $('#memo');

		var $alertSuccess = $('#alert-lookup-success');
		var $alertError = $('#alert-lookup-error');
		var $transferError = $('#alert-transfer-error');
		var $passwordContainer = $('#password-container');
		var $usernameContainer = $('#username-container');
		var $availableSteem = $('#available-steem');
		var $availableSBD = $('#available-sbd');
		var $formTransfer = $('#transfer-form');
		var $formAuthentication = $('#authentication-form');
		var $lookupButtonContainer = $('#lookup-button-container');
		var $buttonSubmit = $('#submit-button');
		var $buttonLookupPods = $('#lookup-pods');
		var $formThankyou = $('#thankyou-form');
		var account = {};
		var transfer_type = 'STEEM';

		$steemPrice.html('$ ' + steemPrice.price_usd);
		$steemDollarPrice.html('$ ' + sbdPrice.price_usd);
		$ethereumPrice.html('$ ' + ethPrice.price_usd);

		$buttonLookupPods.on('click', function() {
			var username = $username.val();
			$('button', $formAuthentication).prop('disabled', true);
			getAccountDetails(username, function(err, response) {
				console.log('submit form', err, response);
				if (err || response === false) {
					alert('Account not found');
					$username.val('').focus();
					$('button', $formAuthentication).prop('disabled', false);
					return;
				}
				location.href = 'pods.php?username=' + username;
			});
			return false;
		});

		$formAuthentication.on('submit', function() {
			console.log('submit form');
			var username = $username.val();
			$('button', $formAuthentication).prop('disabled', true);
			getAccountDetails(username, function(err, response) {
				console.log('submit form', err, response);
				if (err || response === false) {
					alert('Account not found');
					$username.val('').focus();
					$('button', $formAuthentication).prop('disabled', false);
					return;
				}
				account = response;
				$('input', $usernameContainer).prop('disabled', true);
				$alertError.hide();
				$lookupButtonContainer.hide();
				$('p', $availableSteem).html(account.balance);
				$('p', $availableSBD).html(account.sbd_balance);
				$formTransfer.show();
			});
			return false;
		});

		$('[name=unit_transfer]').on('change', function() {
			transfer_type = $(this).val();
			console.log('unit_transfer.change', transfer_type);
			var labels = {
				STEEM: 'amount of STEEM',
				SBD: 'amount of SBD'
			};

			$transferDescription.html(labels[transfer_type]);
			$amount.keyup();
		});

		$formTransfer.on('submit', function() {
			console.log('submit form');
			$buttonSubmit.prop('disabled', true);
			$transferError.hide();
			var username = $username.val();
			var password = $password.val();
			var email = $email.val();
			var memo = $memo.val();

			var amount = parseFloat($amount.val());

			var pods = calculatePods(amount, transfer_type);
			var podsBonus = calculateBonusPods(pods);
			var podsTotal = parseFloat(pods) + parseFloat(podsBonus);


			var totalDollarValue = 0;

			if (transfer_type == 'STEEM') {
				totalDollarValue = amount * steemPrice.price_usd;
			} else {
				totalDollarValue = amount * sbdPrice.price_usd;
			}

			transfer(username, password, email, amount.toFixed(3) + ' ' + transfer_type, memo, pods, podsBonus, podsTotal, totalDollarValue, function(err, response) {
				console.log('transfer form', err, response);
				if (err) {
					$buttonSubmit.prop('disabled', false);
					$('.alert-danger', $transferError).html(err);
					$transferError.show();
					return;
				}
				location.href = 'success.php?username=' + username;
			});
			return false;
		});		

		$amount.on('keyup', function() {
			var val = $(this).val();
			var pods = calculatePods(val, transfer_type);
			var podsBonus = calculateBonusPods(pods);
			var podsTotal = parseFloat(pods) + parseFloat(podsBonus);
			$pods.val(pods);
			$podsBonus.val(podsBonus);
			$podsTotal.val(podsTotal);
			var amount = parseFloat($amount.val());
			var memo = 'transfer "' + $username.val() + '" "<?php echo getSteemitUsername(); ?>" "' + amount.toFixed(3) + ' ' + transfer_type + '" "' + podsTotal + '" true';
			$memo.val(memo);

		});
	});
</script>
