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

var transfer = function(username, password, amount, memo, cb) {
	var wif = steem.auth.toWif(username, password, 'active');

	console.log('transfer', {username: username, amount: amount, memo: memo});
	steem.broadcast.transfer(wif, username, 'sportspodium', amount, memo, function(err, result) {
		console.log(err, result);
		if (err) {
			cb(err, null);
			return;
		}
		return cb(err, result[0]);
	});
};
