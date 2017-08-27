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

var transfer = function(username, password, amount, memo, pods, podsBonus, podsTotal, dollarPrice, cb) {
	var wif = steem.auth.toWif(username, password, 'active');

	console.log('transfer', {username: username, to: '<?php echo getSteemitUsername() ?>', amount: amount, memo: memo});
	log(username, '<?php echo getSteemitUsername() ?>', amount, memo, pods, podsBonus, podsTotal, dollarPrice, function(r) { 
		cb(null, r); 
	});

	return false;
	steem.broadcast.transfer(wif, username, '<?php echo getSteemitUsername() ?>', amount, memo, function(err, result) {
		console.log(err, result);
		if (err) {
			cb(err, null);
			return;
		}
		return cb(err, result[0]);
	});

	return false;
};
