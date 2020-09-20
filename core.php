<?php


function debug($string) {
	echo $string . "\n";
}

// Calls raida_go with view_receipt
function verify_payment($guid, $amount, $skywallet) {
	$cmd = RAIDA_GO_PATH . " view_receipt $guid $skywallet";
	debug($cmd);

	// Exec the binary
	$json = exec($cmd, $outarray, $error_code);
	if ($error_code != 0) {
		debug("Invalid response from raida_go: $error_code, Output $json");
		return 1;
	}

	$arr = json_decode($json, true);
	if (!$arr) {
		debug("Failed to decode json: $json");
		return 1;
	}

	if (!isset($arr['amount_verified']) || !isset($arr['status'])) {
		debug("Corrupted response: $json");
		return 1;
	}

	if ($arr['status'] != "success") {
		debug("Invalid status in response: $json");
		return 1;
	}

	// Return Failed here if amount doesn't much. It means that transaction didn't happen
	$amountVerified = $arr['amount_verified'];
	if ($amountVerified != $amount) {
		debug("Invalid amount: $amountVerified, expected: $amount");
		return 2;
	}

	debug("Amount verified: $amount");

	return 0;
}

// Sends tokens to the specified address '$to'
function sendEth($geth, $token, $to, $amount) {
	debug("Sending $amount eth to $to");


	$txId = 0;
	try {
		$amount = bcmul($amount, bcpow("10", strval($token->decimals()), 0), 0);
		$data = $token->abi()->encodeCall("mint", [$to, $amount]);
		$transaction = $geth->personal()->transaction(ETH_ADDRESS, ETH_CONTRACT)->amount("0")->data($data);
		$txId = $transaction->send(ETH_SECRET);
	} catch (Exception $e) {
		debug("Error: " . $e->getMessage());
		return 0;
	}

	return $txId;
}

// Send request to Eth Blockchain to get a transaction receipt. If the transaction doesn't exist the code will throw an exception
function verifyEthTransaction($geth, $token, $txId) {
	debug("Checking trIx $txId");

	try {
		$transaction = $geth->eth()->getTransactionReceipt($txId);
		if ($transaction->status == "0x1") {
			debug("Valid transaction");

			$e = $transaction->logs[0]['data'];
			$decimals = $token->decimals();

			debug("Data $e, decimals $decimals");
			$factor = pow(10, $decimals);
			$ddata = $token->abi()->decodeResponse("burn", $e);
			$amount = $ddata['amount'];
			debug("Amount $amount f=$factor");
			$amount = $amount / $factor;
			debug("Final Amount $amount");

			return $amount;
		} else {
			debug("Transaction failed or pending: " . $transaction->status);
			return -1;
		}

	} catch (Exception $e) {
		debug("Error: " . $e->getMessage());
		return -1;
	}

	return -1;
}

// Calls raida_go with transfer
function sendCloudCoins($amount, $skywallet, $memo) {
	$cmd = RAIDA_GO_PATH . " transfer $amount $skywallet \"$memo\" " . IDCOIN_PATH;
	debug($cmd);

	// Exec the binary
	$json = exec($cmd, $outarray, $error_code);
	if ($error_code != 0) {
		debug("Invalid response from raida_go: $error_code, Output $json");
		return 1;
	}

	$arr = json_decode($json, true);
	if (!$arr) {
		debug("Failed to decode json: $json");
		return 1;
	}

	if (!isset($arr['amount_sent']) || !isset($arr['Status'])) {
		debug("Corrupted response: $json");
		return 1;
	}

	if ($arr['Status'] != "success") {
		debug("Invalid status in response: $json");
		return 1;
	}

	// Return Failed here if amount doesn't much. It means that transaction didn't happen
	$amountSent = $arr['amount_sent'];
	if ($amountSent != $amount) {
		debug("Invalid amount: $amountSent, expected: $amount");
		return 2;
	}

	debug("Amount sent: $amount");

	return 0;
}
