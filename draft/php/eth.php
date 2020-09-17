<?php

require_once "globals.php";
require_once "db.php";


function eth_verify_wallet($addr, $sig) {

	// TODO verify if the signature is valid for the address
	return true;
};


function eth_create_receipt($addr, $amnt) {

	$uuid = db_new_id();

	$time = time();
	$data = array("recipient" => $addr, "amount" => $amnt, "time" => $time, "uuid" => $uuid);

	// TODO hash the data and sign it using the private key of the server wallet
	$hash = hash("sha256", $data);
	$signature = "";

	$ret = array("data" => $data, "hash" => $hash, "sig" => $signature, "status" => STATUS_UNSENT, "txid" => "");

	return $ret;
};


function eth_check_burn($addr, $txid) {

	// TODO check the eth blockchain for the transaction with the log output of the tokens burned

	return -1;
};


?>
