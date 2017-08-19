	var eth = {};
	var steem = {};
	function getEthereumPrice() {
		return $.get('https://coinmarketcap-nexuist.rhcloud.com/api/eth')
			.then(function(result) {
				console.log('ethereum.get', result);
				eth = result;
			});

	}

	function getSteemPrice() {
		return $.get('https://coinmarketcap-nexuist.rhcloud.com/api/Steem')
			.then(function(result) {
				console.log('steem.get', result);
				steem = result;
			});
	}

	function steemPerPod() {
		var ethDollar = eth.price.usd;
		var steemDollar = steem.price.usd;

		var podsPerEthereum = 2000;
		var dollarPerPod = podsPerEthereum / ethDollar;
		var amount = 1;
		// Ethereum price point :  $288.11
		// Steem Price : $1.12
		// Steem Dollar Price: $0,96


		// 1 Ethereum = 2000 Pods
		// 1 Ethereum = 257,2411 Steem

		var steemCost = (dollarPerPod / steemDollar) * amount;

		console.log('steemCost', steemCost);		
		return steemCost;
	}

	function calculatePods(amount, currency) {
		var totalCost = steemPerPod() * amount;
		console.log('totalCost', totalCost);
		return totalCost;
	}

	$.when(
		getEthereumPrice(),
		getSteemPrice()).then(function() {
			
		});