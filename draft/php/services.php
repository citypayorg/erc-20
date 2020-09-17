<?php

require_once "globals.php";
require_once "db.php";
require_once "eth.php";
require_once "raida.php";

function get_balance($addr) {

	$bal = db_get_balance($addr);

	_ok(array("balance" => $bal);

	return true;
};

function auth($addr, $sig) {

	if (!eth_verify_wallet($addr, $sig)) {

		_error("ERR_AUTH_WALLET_INVALID");
		return false;
	};

	_ok(array("token" => _auth_create($addr));
	return true;
};

function auth_refresh($addr) {

	if (_auth_status() != "OK") {
		_error(_auth_status());
		return false;
	};

	_ok(array("token" => _auth_create($addr));
	return true;
};

function add_deposit($addr, $uuid, $amount) {

	if (db_has_deposit($uuid)) {
		_error("ERR_DEPOSIT_EXISTS");
		return false;
	};

	$ret = raida_check_deposit($uuid, $amount);
	if (!$ret) {
		_error("ERR_DEPOSIT_INVALID");
		return false;
	};

	db_add_deposit($addr, $uuid, $amount);

	_ok();
	return true;
};

function mint($addr, $amnt) {

	// TODO make this atomic
	if (_auth_status() != "OK") {
		_error(_auth_status());
		return false;
	};

	$bal = db_get_balance($addr);
	if ($bal < $amnt) {

		_error("ERR_WITHDRAW_INSUFFICIENT_FUNDS");
		return false;
	};

	$rcpt = eth_create_receipt($addr, $amnt);

	db_add_receipt($addr, $amnt, $rcpt);

	_ok($rcpt);
	return true;
};

function get_receipt($addr, $uuid) {

	if (_auth_status() != "OK") {
		_error(_auth_status());
		return false;
	};

	$list = db_get_receipt($addr, $uuid);

	_ok($list);
	return true;
};

function get_receipts($addr, $status_filter) {

	if (_auth_status() != "OK") {
		_error(_auth_status());
		return false;
	};

	$list = db_get_receipts($addr, $status_filter);
	_ok($list);
	return true;
};

function receipt_set_status($addr, $receipt, $status, $txid) {

	if (_auth_status() != "OK") {
		_error(_auth_status());
		return false;
	};

	if ($status < 0 || $status >= STATUS_MAX) {
		_error("ERR_RECEIPT_INVALID_STATUS");
		return false;
	};

	if (is_null(db_get_receipt($addr, $receipt))) {
		_error("ERR_RECEIPT_INVALID_UUID");
		return false;
	};

	db_receipt_update($receipt, $status, $txid);

	_ok();
	return true;
};

function add_burn($addr, $txid) {

	if (db_has_burn($txid)) {
		_error("ERR_BURN_EXISTS");
		return false;
	};

	$amt = eth_check_burn($addr, $txid);
	if ($amt < 0) {
		_error("ERR_BURN_INVALID");
		return false;
	};

	db_add_burn($addr, $txid, $amt);
	return true;
};

function get_withdraws($addr) {

	if (_auth_status() != "OK") {
		_error(_auth_status());
		return false;
	};

	$list = db_get_withdraws($addr);

	_ok($list);
	return true;
};

function add_withdraw($addr, $amnt) {

	if (_auth_status() != "OK") {
		_error(_auth_status());
		return false;
	};

	$bal = db_get_balance($addr);
	if ($bal < $amnt) {

		_error("ERR_WITHDRAW_INSUFFICIENT_FUNDS");
		return false;
	};

	$stack = raida_add_withdraw($amnt);
	if (is_null($stack)) {
		// unexpected error, this shouldn't happen
		_error("ERR_WITHDRAW_UNEXPECTED");
		return false;
	};

	$uuid = db_add_withdraw($addr, $amnt, $stack);

	_ok(array("uuid" => $uuid));

	return true;
};

function get_stack($addr, $uuid) {

	if (_auth_status() != "OK") {
		_error(_auth_status());
		return false;
	};

	$file = db_get_stack($addr, $uuid);
	if (is_null($file)) {

		_error("ERR_STACK_INVALID");
	};

	send_file($file);
	return true;
};

?>
