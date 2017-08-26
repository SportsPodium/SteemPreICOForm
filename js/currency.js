	var ethPrice = {};
	var steemPrice = {};
	function getEthereumPrice() {
		return $.get('https://coinmarketcap-nexuist.rhcloud.com/api/eth')
			.then(function(result) {
				console.log('ethereum.get', result);
				ethPrice = result;
			});

	}

	function getSteemPrice() {
		return $.get('https://coinmarketcap-nexuist.rhcloud.com/api/Steem')
			.then(function(result) {
				console.log('steem.get', result);
				steemPrice = result;
			});
	}

	function steemPerPod() {
		var steemDollar = steemPrice.price.usd;

		var dpp = dollarPerPod();
		var amount = 1;
		var steemCost = (dpp / steemDollar) * amount;

		console.log('steemCost', steemCost);		
		return steemCost;
	}

	function dollarPerPod() {
		var ethDollar = ethPrice.price.usd;
		var steemDollar = steemPrice.price.usd;

		var podsPerEthereum = 2000;
		var dpp = ethDollar / podsPerEthereum;
		return dpp;
	}	

	function calculatePods(amount, currency) {
		var totalCost = steemPerPod() * amount;
		console.log('totalCost', totalCost);
		return totalCost;
	}
