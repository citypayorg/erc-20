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
$abiPath = dirname(__FILE__) . "/erc20.abi";
$erc20 = new ERC20($geth);
$erc20->abiPath($abiPath);
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

	$signature = sendEth($geth, $token, $tr['ethaccount'], $tr['amount'], $tr['id']);
	if ($signature === 0) {
		debug("Failed to send Eth for #" . $tr['id']);
		continue;
	}

	debug("Updating...");
//	$db->setTransactionTrx($tr['id'], $trId);
	$db->setTransactionSignature($tr['id'], $signature);
}

debug("Getting new Eth transactions");
$trs = $db->getNewEthTransactions();
foreach ($trs as $tr) {
	debug("Proccessing transaction #" . $tr['id']);

	// returns amount + from
	// -1 - failed to query raida_go or network error. We will not mark the transaction and try again later
	$rv = verifyEthTransaction($geth, $token, $tr['ethtxid'], $tr['skywallet'], $tr['signature']);
	if ($rv == -1) {
		continue;
	}

	$amount = $rv[0];
	$from = $rv[1];

	$db->updateTransactionStatusAndAmount($tr['id'], TR_STATUS_VERIFIED, $amount, $from);
}

debug("Getting verified Eth transactions");
$trs = $db->getVerifiedEthTransactions();
foreach ($trs as $tr) {
	debug("Proccessing transaction #" . $tr['id']);

	// 0 - success
	// 1 - failed to query raida_go or network error. We will not mark the transaction and try again later
	// 2 - permanent fail
	$rv = sendCloudCoins($tr['amount'], $tr['skywallet'], "Transfer for eth #" . $tr['ethtxid']);
	if ($rv == 0) {
		$status = TR_STATUS_SENT;
	} else if ($rv == 1) {
		continue;
	} else if ($rv == 2) {
		$status = TR_STATUS_FAILED;
	} else {
		die("Internal error");
	}

	$db->updateTransactionStatus($tr['id'], TR_STATUS_SENT);
}
