var getAccountDetails = function(username, cb) {
	console.log('getAccounts', username);
	steem.api.getAccounts([username], function(err, result) {
		console.log(err, result);
		if (err) {
			return cb(err, null);
		}
		return cb(err, result[0]);

	});
};

var transfer = function(username, password, to, amount, memo, cb) {
	var wif = steem.auth.toWif(username, password, 'active');

	steem.broadcast.transfer(wif, username, to, amount, memo, function(err, result) {
		console.log(err, result);
		if (err) {
			return cb(err, null);
		}
		return cb(err, result[0]);
	});
};
