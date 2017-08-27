<form class="form-horizontal" id="authentication-form">
	<div class="form-group" id="username-container">
		<label for="username" class="col-sm-4 control-label">steemit username</label>
		<div class="col-sm-6">
			<div class="input-group">
				<span class="input-group-addon">@</span>
				<input type="text" class="form-control" id="username" required placeholder="steemit username">
			</div>
		</div>
	</div>
	<div class="form-group" id="lookup-button-container">
		<div class="col-sm-offset-4 col-sm-6">
			<button type="submit" class="btn btn-primary">Lookup Account</button>
		</div>
	</div>
	<div id="alert-lookup-success" class="form-group" style="display: none;">
		<div class="col-sm-12">
			<div class="alert alert-success">Account Found</div>
		</div>
	</div>
	<div id="alert-lookup-error" class="form-group" style="display: none;">
		<div class="col-sm-12">
			<div class="alert alert-error">Account lookup Failed</div>
		</div>
	</div>
</form>
<form class="form-horizontal" style="display: none;" id="transfer-form">
	<div class="form-group">
		<label for="password" class="col-sm-4 control-label">current ethereum $ price</label>
		<div class="col-sm-6">
			<p class="form-control-static" id="eth-price">unknown</p>
		</div>
	</div>

	<div class="form-group">
		<label for="password" class="col-sm-4 control-label">current steem $ price</label>
		<div class="col-sm-6">
			<p class="form-control-static" id="steem-price">unknown</p>
		</div>
	</div>


	<div class="form-group" id="available-steem">
		<label for="password" class="col-sm-4 control-label">available steem</label>
		<div class="col-sm-6">
			<p class="form-control-static">unknown</p>
		</div>
	</div>
	<div class="form-group" id="available-sbd">
		<label for="password" class="col-sm-4 control-label">available SBD</label>
		<div class="col-sm-6">
			<p class="form-control-static">unknown</p>
		</div>
	</div>

	<div class="form-group">
		<label for="password" class="col-sm-4 control-label">what to transfer</label>
		<div class="col-sm-6">
			<label class="radio-inline">
				<input type="radio" name="unit_transfer" id="unit_transfer_steem" value="STEEM" checked> STEEM
			</label>
			<label class="radio-inline">
				<input type="radio" name="unit_transfer" id="unit_transfer_sbd" value="SBD"> SBD
			</label>
		</div>
	</div>

	<div class="form-group">
		<label for="amount" class="col-sm-4 control-label" id="transfer-description">amount of steem</label>
		<div class="col-sm-6">
			<input type="number" class="form-control" id="amount" required placeholder="amount">
		</div>
	</div>

	<div class="form-group">
		<label for="amount" class="col-sm-4 control-label" >pods</label>
		<div class="col-sm-6">
			<input type="number" class="form-control" id="pods" disabled placeholder="pods">
		</div>
	</div>

	<div class="form-group">
		<label for="amount" class="col-sm-4 control-label" id="transfer-description">bonus pods</label>
		<div class="col-sm-6">
			<input type="number" class="form-control" id="bonus-pods" disabled placeholder="bonus pods">
		</div>
	</div>

	<div class="form-group">
		<label for="amount" class="col-sm-4 control-label" id="transfer-description">total pods</label>
		<div class="col-sm-6">
			<input type="number" class="form-control" id="total-pods" disabled placeholder="total pods">
		</div>
	</div>

	<div class="form-group">
		<label for="password" class="col-sm-4 control-label">transfering to</label>
		<div class="col-sm-6">
			<div class="input-group">
				<span class="input-group-addon">@</span>
				<input disabled type="text" class="form-control" id="to" placeholder="steemit username" value="<?php echo getSteemitUsername() ?>">
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="password" class="col-sm-4 control-label">memo</label>
		<div class="col-sm-6">
			<input disabled type="text" class="form-control" id="memo" placeholder="memo" value="">
		</div>
	</div>
	<div class="form-group" id="password-container">
		<label for="password" class="col-sm-4 control-label">steemit password</label>
		<div class="col-sm-6">
			<input type="password" class="form-control" id="password" required placeholder="password">
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-6">
			<button id="submit-button" type="submit" class="btn btn-primary">Make Contribution</button>
		</div>
	</div>
</form>

<form class="form-horizontal" style="display: none;" id="thankyou-form">
	<div class="form-group" id="available-steem">
		<label for="password" class="col-sm-4 control-label">available steem</label>
		<div class="col-sm-6">
			<p class="form-control-static">unknown</p>
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

		var $username = $('#username');
		var $password = $('#password');
		var $amount = $('#amount');
		var $pods = $('#pods');
		var $podsTotal = $('#total-pods');
		var $podsBonus = $('#bonus-pods');
		var $memo = $('#memo');

		var $alertSuccess = $('#alert-lookup-success');
		var $alertError = $('#alert-lookup-error');
		var $passwordContainer = $('#password-container');
		var $usernameContainer = $('#username-container');
		var $availableSteem = $('#available-steem');
		var $availableSBD = $('#available-sbd');
		var $formTransfer = $('#transfer-form');
		var $lookupButtonContainer = $('#lookup-button-container');
		var $buttonSubmit = $('#submit-button');
		var account = {};
		var transfer_type = 'STEEM';

		$.when(
			getEthereumPrice(),
			getSteemPrice()).then(function() {
				$steemPrice.html('$ ' + steemPrice.price.usd);
				$ethereumPrice.html('$ ' + ethPrice.price.usd);
			});

		$('#authentication-form').on('submit', function() {
			console.log('submit form');
			var username = $username.val();
			getAccountDetails(username, function(err, response) {
				console.log('submit form', err, response);
				if (err || response === false) {
					alert('Account not found');
					$username.val('').focus();
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
		});

		$formTransfer.on('submit', function() {
			console.log('submit form');
			$buttonSubmit.prop('disabled', true);
			var username = $username.val();
			var password = $password.val();
			var memo = $memo.val();
			var pods = $podsTotal.val();
			var amount = parseFloat($amount.val());

			transfer(username, password, amount.toFixed(3) + ' ' + transfer_type, memo, pods, function(err, response) {
				console.log('transfer form', err, response);
				if (err) {
					$buttonSubmit.prop('disabled', false);
					return;
				}
				$formTransfer.hide();
				$formThankyou.show();
			});
			return false;
		});		

		$amount.on('change', function() {
			var val = $(this).val();
			var pods = calculatePods(val, transfer_type);
			var podsBonus = calculateBonusPods(pods);
			var podsTotal = pods + podsBonus;
			$pods.val(pods);
			$podsBonus.val(podsBonus);
			$podsTotal.val(podsTotal);
			var amount = parseFloat($amount.val());
			var memo = 'transfer "' + $username.val() + '" "<?php echo getSteemitUsername(); ?>" "' + amount.toFixed(3) + ' ' + transfer_type + '" "' + podsTotal + '" true';
			$memo.val(memo);

		});
	});
</script>
