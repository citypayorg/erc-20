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
if (!isset($_GET['skywallet']))
	die("Skywallet is not defined");

if (!isset($_GET['tx']))
	die("Tx is not defined");

if (!isset($_GET['sig']))
	die("Signature is not defined");

$tx = $_GET['tx'];
if (!preg_match("/^0x[a-fA-F0-9]{64}$/", $tx))
	die("Invalid Tx");

$sig = $_GET['sig'];
if (!preg_match("/^0x[a-fA-F0-9]+$/", $tx))
	die("Invalid Tx");

$skywallet = $_GET['skywallet'];
$ip = gethostbyname($skywallet);
if (!filter_var($ip, FILTER_VALIDATE_IP))
	die("Failed to resolve skywallet");


if (!$db->putRecord("", 0, $skywallet, "", DIR_ETH, $tx, $sig))
	die("Failed to put record in the Database");

header('Location: /eth/successeth.php');
