<?php

ini_set('display_errors', 1);

// Module checks
require_once "checks.php";

// Configuration
require_once "config.php";

// Database class
require_once "db.php";

// Core
require_once "core.php";

// Connection to the Database
$db = new DB(DB_HOST, DB_NAME, DB_USER, DB_PASS);
if (!isset($_GET['account']))
	die("Account is not defined");

if (!isset($_GET['amount']))
	die("Amount is not defined");

if (!isset($_GET['guid']))
	die("GUID is not defined");

if (!isset($_GET['merchant_skywallet']))
	die("Merchant skywallet is not defined");


$ethAccount = trim($_GET['account']);
if (!preg_match("/^0x[a-fA-F0-9]{40}$/", $ethAccount))
	die("Invalid Account");

$amount = intval($_GET['amount']);
if ($amount <= 0) 
	die("Invalid amount");

$guid = trim($_GET['guid']);
if (!preg_match("/^[0-9a-f]{32}$/", $guid))
	die("Invalid GUID");

$merchant = $_GET['merchant_skywallet'];
$ip = gethostbyname($merchant);
if (!filter_var($ip, FILTER_VALIDATE_IP))
	die("Failed to resolve skywallet");

// No matter where they sent to, we specify our own wallet
$merchant = CC_WALLET;

$id = $db->putRecord($guid, $amount, $merchant, $ethAccount, DIR_CC, "", "");
if (!$id)
	die("Failed to put record in the Database");

header('Location: /eth/success.php?trid='. $id);
