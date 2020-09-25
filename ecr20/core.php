<?php

use EthereumRPC\Contracts\ABI\DataTypes;
require_once 'ecrecover_helper.php';


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

function stringToBinary($string)
{
$characters = str_split($string);
$binary = [];
foreach ($characters as $character) {
$data = unpack('H*', $character);
$binary[] = base_convert($data[1], 16, 2);
}
return $binary;
}

// Sends tokens to the specified address '$to'
function sendEth($geth, $token, $to, $amount, $id) {
	debug("Sending $amount eth to $to (id $id)");

	try {
		$amount = bcmul($amount, bcpow("10", strval($token->decimals()), 0), 0);	
	} catch (Exception $e) {
		debug("Error: " . $e->getMessage());
		return 0;
	}
	$message = "$to$amount$id";

	debug($amount);

	$v0 = substr($to, 2);
	$v1 = DataTypes::Encode("uint256", $amount);
	$v2 = DataTypes::Encode("uint256", $id);

	$string = "0x$v0$v1$v2";
	debug("hashing $string");

	//$hash = "0x" . hash('sha256', hex2bin($string));
	//$hash = hash('sha256', hex2bin($string));
	//debug("h=$hash");
	$hash = $string;

	try {
	        $request = $geth->jsonRPC("eth_sign", null, [ETH_ADDRESS, $hash]);
        	$signature = $request->get("result");
		debug("signature $signature" );

		return $signature;
	} catch (Exception $e) {
		debug("Error: " . $e->getMessage());
		return 0;
	}


	return 0;
/*
	exit;
	$txId = 0;
	try {
		$data = $token->abi()->encodeCall("mint", [$to, $amount]);
		$transaction = $geth->personal()->transaction(ETH_ADDRESS, ETH_CONTRACT)->amount("0")->data($data);
		$txId = $transaction->send(ETH_SECRET);
	} catch (Exception $e) {
		debug("Error: " . $e->getMessage());
		return 0;
	}

	return $txId;
*/
}

// Send request to Eth Blockchain to get a transaction receipt. If the transaction doesn't exist the code will throw an exception
function verifyEthTransaction($geth, $token, $txId, $skywallet, $signature) {
	debug("Checking trIx $txId");

	if (!$signature) {
		debug("No signature");
		return -1;
	}

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

			$tr = $geth->eth()->getTransaction($txId);
			$from = $tr->from;

			debug("From: $from, $skywallet");
			$enc = "$from:$skywallet";
			$presha_str = hex2bin(substr(keccak256('string Message'), 2) . substr(keccak256($enc), 2));
			$hex = keccak256($presha_str);
			$swallet = ecRecover($hex, $signature);
			if ($swallet != $from) {
				debug("Invalid signature");
				return -1;
			}

			return [$amount, $from];
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
