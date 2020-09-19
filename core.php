<?php


function debug($string) {
	echo $string . "\n";
}

function verify_payment($guid, $amount, $skywallet) {
	$cmd = RAIDA_GO_PATH . " view_receipt $guid $skywallet";
	debug($cmd);

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


function sendEth($geth, $token, $to, $amount) {
	debug("Sending $amount eth to $to");

	try {
		$data = $token->encodedTransferData($to, $amount);
		$transaction = $geth->personal()->transaction(ETH_ADDRESS, ETH_CONTRACT)->amount("0")->data($data);
		//$transaction = $geth->personal()->transaction(ETH_ADDRESS, "0x81b7e08f65bdf5648606c89998a9cc8164397647")->amount("0")->data($data);
		//$transaction = $geth->personal()->transaction(ETH_ADDRESS, $to)->amount("0")->data($data);
		$txId = $transaction->send(ETH_SECRET);

		print_r($txId);
	} catch (Exception $e) {
		debug("Error: " . $e->getMessage());
		return 1;
	}

	return 0;
}

