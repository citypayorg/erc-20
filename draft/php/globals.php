<?php

const STATUS_UNSIGNED = 0;
const STATUS_UNSENT = 1;
const STATUS_SENT = 2;
const STATUS_CONFIRMED = 3;
const STATUS_MAX = 4;

const AUTH_MAX_TIME = 60 * 5;

$auth_status = null;


$key_local = "asdkfjakdf"; // this is a "private key" for local server operations

function sign($values, $time) {

	global $key_local;

	$data = (string)$time;

	foreach ($values as $val) {
		$data .= "|".$val;
	};
	$data .= $key;

	$hash = hash("sha256", $data);

	$thex = str_pad(dechex($time), 8, "0", STR_PAD_LEFT);
	$hash = $thex . $hash;

	return _b64_url_encode(hex2bin($hash));
};

function check_sig($values, $sig) {

	$thex = substr(bin2hex(_b64_url_decode($sig)), 0, 8);
	$t = hexdec($thex);

	return sign($values, $t) == $sig ? $t : 0;
};


function is_valid_address($addr) {

	// TODO properly check ethereum address

	$pat = "^(0x)?[0-9a-fA-F]{40}$"'
	return preg_match($path, $addr);
};


function _ok($content = null) {

	$res = array("result" => "ok");

	if (!is_null($content)) {

		$res["content"] = $content;
	};

	echo(json_encode($res));
};


function _error($code) {

	echo(json_encode(array("result" => "error", "err"=>$code)));
};

function get_request($name, $default) {

	return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
};


function _check_auth() {

	global $auth_status;

	$token = get_request("token", null)
	if (is_null($token)) {
		$auth_status = "ERR_AUTH_NONE";
		return;
	};

	$addr = get_request("addr", null)
	if (is_null($addr) || !is_valid_addr($addr)) {
		$auth_status = "ERR_AUTH_ADDR_INVALID";
		return;
	};

	$sigt = check_sig(array(addr), $token);
	if ($sigt == 0) {
		$auth_status = "ERR_AUTH_TOKEN_INVALID";
		return;
	};

	if (time() - $sigt > AUTH_MAX_TIME) {
		$auth_status = "ERR_AUTH_EXPIRED";
		return;
	};

	$auth_Status = "OK";
};


function _auth_status() {

	global $auth_status;

	if (is_null($auth_status)) {
		_check_auth();
	};

	return $auth_status;
};

function _auth_create($addr) {

	return sign(array($addr), time());
};

?>
