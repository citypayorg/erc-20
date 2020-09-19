<?php

// Module checks
require_once "checks.php";

// Configuration
require_once "config.php";

// Database class
require_once "db.php";

// Core
require_once "core.php";

// Composer Deps
require __DIR__ . '/vendor/autoload.php';

use EthereumRPC\EthereumRPC;
use ERC20\ERC20;

$geth = new EthereumRPC(ETH_HOST, ETH_PORT);
$erc20 = new ERC20($geth);
$token = $erc20->token(ETH_CONTRACT);


$fp = @fsockopen(ETH_HOST, ETH_PORT, $errno, $errstr, 5);
if (!$fp) 
	die('Eth Wallet is not listening on the port ' . ETH_PORT);

fclose($fp);

// Connection to the Database
$db = new DB(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$raida_go = RAIDA_GO_PATH;
if (!file_exists($raida_go))
	die("Raida Go not found");

if (!is_executable($raida_go))
	die("Raida Go doesn't have exec permissions");

debug("Getting new transactions");
$trs = $db->getNewTransactions();
foreach ($trs as $tr) {
	debug("Proccessing transaction #" . $tr['id']);
	// 0 - success
	// 1 - failed to query raida_go or network error. We will not mark the transaction and try again later
	// 2 - transaction doesn't exists or amount is invalid, we will mark it as failed
	$rv = verify_payment($tr['guid'], $tr['amount'], $tr['skywallet']);
	if ($rv == 0) {
		$status = TR_STATUS_VERIFIED;
	} else if ($rv == 1) {
		continue;
	} else if ($rv == 2) {
		$status = TR_STATUS_FAILED;
	} else {
		die("Internal error");
	}

	$db->updateTransactionStatus($tr['id'], $status);
}

debug("Getting verified transactions");
$trs = $db->getVerifiedTransactions();
foreach ($trs as $tr) {
	debug("Proccessing transaction #" . $tr['id']);

	// 0 - success
	// 1 - fail
	$rv = sendEth($geth, $token, $tr['ethaccount'], $tr['amount']);

	print_r($rv);

}
