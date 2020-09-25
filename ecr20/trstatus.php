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
if (!isset($_GET['id']))
	die("");

$id = intval($_GET['id']);
if ($id <= 0)
	die("");

$tr = $db->getTransaction($id);
if (!$tr)
	die("{}");

echo json_encode($tr);
