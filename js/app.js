var getAccountDetails = function(username, cb) {
	console.log('getAccounts', username);
	steem.api.getAccounts([username], function(err, result) {
		console.log(err, result);
		if (err || result.length == 0) {
			cb(err, false);
			return;
		}
		return cb(err, result[0]);

	});
};

var log = function(username, target, amount, memo, pods, podsBonus, podsTotal, dollarPrice, cb) {
	var ethDollar = ethPrice.price_usd;
	var steem = steemPrice.price_usd;
	var sbd = sbdPrice.price_usd;
	var d = dollarPerPod();

	$.ajax({
		method: 'POST',
		url: '/purchase.php',
		data: {
			ethPrice: ethPrice,
			steemPrice: steemPrice,
			sbdPrice: sbdPrice,
			eth: ethDollar,
			steem: steem,
			sbd: sbd,
			username: username,
			target: target,
			amount: amount,
			memo: memo,
			pods: pods,
			podsBonus: podsBonus,
			podsTotal, podsTotal,
			dollarPerPod: d,
			dollarPrice: dollarPrice,
		},
		success: function(result) {
			console.log('log.success', result);
			cb(result);
		},
		error: function(err) {
			console.log('log.error', err);
			cb(null, err);
		}
	});
};

var check = function(amount, successCallback, failedCallback) {
	$.ajax({
		method: 'POST',
		url: '/check.php',
		data: {
			amount: amount
		},
		success: function(r) {
			var result = JSON.parse(r);
			console.log('check.success', result);
			if (result.success) {
				successCallback();
			} else {
				failedCallback();
			}
		}
	})
}

var transfer = function(username, password, amount, memo, pods, podsBonus, podsTotal, dollarPrice, cb) {
	var wif = steem.auth.toWif(username, password, 'active');

	console.log('transfer', {username: username, to: '<?php echo getSteemitUsername() ?>', amount: amount, memo: memo, pods: pods, podsBonus: podsBonus, podsTotal: podsTotal, dollarPrice: dollarPrice});

	check(dollarPrice, function() {

		steem.broadcast.transfer(wif, username, '<?php echo getSteemitUsername() ?>', amount, memo, function(err, result) {
			console.log(err, result);
			if (err) {
				if (err.message.indexOf('missing required active authority') >= 0) {
					cb('Invalid password, please try again', null);
					return;
				}
				if (err.message.indexOf('Account does not have sufficient funds') >= 0) {
					cb('Account does not have sufficient funds for transfer', null);
					return;
				}
				cb(err.message, null);
				return;
			}
			log(username, '<?php echo getSteemitUsername() ?>', amount, memo, pods, podsBonus, podsTotal, dollarPrice, function(r) { 
				cb(null, result[0]); 
			});			
		});



	}, function() {
		cb('Not enough PODS for sale');
	})

	return false;

	return false;
};
