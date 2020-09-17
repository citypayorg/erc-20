<?php

require_once "globals.php";

function db_new_id() {

	$db = db_get_db();
	$db->autocommit(false);

	$res = $db->query("select uuid from globals for update");
	$ret = $res->fetch_row()[0];

	$db->query("update globals set uuid=uuid+1");
	$db->commit();

	return $ret;
};

function db_check_addr($addr) {

	$db = db_get_db();
	$res = $db->query("select * from wallets where addr = '$addr'");

	if ($res->num_rows == 0) {

		$db->query("insert into wallets (addr, balance) values ('$addr', 0)");
		$res = $db->query("select * from wallets where addr = '$addr'");
	};

	return $res->fetch_assoc();
};

function db_get_balance($addr) {

	$db = db_get_db();
	$user = db_check_addr($addr);

	return $user['balance'];

};

function db_has_deposit($uuid) {

	$db = db_get_db();
	$res = $db->query("select id from deposits where uuid='$uuid'");
	return $res->num_rows > 0;
};

function db_add_deposit($addr, $uuid, $amount) {

	$db = db_get_db();

	$db->autocommit(false);
	$res = $db->query("insert into deposits (addr, uuid, amount) values ('$addr', '$uuid', $amount)");
	if (!$res) {
		_debug("db_add_deposit insert error: ".$db->error);
		$db->rollback();
		return false;
	};
	$res = $db->query("update wallets set balance=balance + $amount");
	if (!$res) {
		_debug("db_add_deposit balance error: ".$db->error);
		$db->rollback();
		return false;
	};

	$db->commit();

	return true;
};

function db_add_receipt($addr, $amnt, $rcpt) {

	// needs to be atomic
};

function _as_receipt($row) {

	$data = array("recipient" => $row['addr', "amount" => $row['amnt'], "time" => $row['time'], "uuid" => $row['uuid']);
	$ret = array("data" => $data, "hash" => $row['hash'], "sig" => $row['signature'], "status" => $row['status'], "txid" => $row['txid']);

	return $ret;

};

function db_get_receipt($addr, $uuid) {

	$db = db_get_db();
	$res = $db->query("select * from receipts where addr = '$addr' and uuid = '$uuid'");
	if (!$res) return array();
	if ($res->num_rows == 0) return array();

	$row = $res->fetch_assoc();
	return _as_receipt($row);
};

function db_get_receipts($addr, $status_filter = -1) {

	$db = db_get_db();
	$ret = array();

	$query = "select uuid from receipts where addr = '$addr'";
	if ($status_filter != -1) {
		$query .= " and status = $status_filter";
	};
	$res = $db->query($query);

	while($row = $res->fetch_row()) {

		array_push($ret, $row[0]);
	};

	return $ret;
};

function db_receipt_update($uuid, $status, $txid) {

	if (($status < 0 || $status >= STATUS_MAX) && $txid == "") {
		return false;
	};

	$db = db_get_db();
	$query = "update receipts set ";
	$sep = "";
	if ($status != -1) {
		$query .= "status = $status";
		$sep = "and";
	};
	if ($txid == "") {
		$query .= "$sep txid = '$txid'";
	};

	return $db->query($query);
};

function db_has_burn($txid) {

	$db = db_get_db();
	$res = $db->query("select id from burns where txid='$txid'");
	return $res->num_rows > 0;
};

function db_add_burn($addr, $txid, $amt) {

	$db = db_get_db();

	$db->autocommit(false);
	$res = $db->query("insert into burns (addr, txid, amount) values ('$addr', '$txid', $amount)");
	if (!$res) {
		_debug("db_add_burn insert error: ".$db->error);
		$db->rollback();
		return false;
	};
	$res = $db->query("update wallets set balance=balance + $amount");
	if (!$res) {
		_debug("db_add_burn balance error: ".$db->error);
		$db->rollback();
		return false;
	};

	$db->commit();

	return true;
};

function db_get_withdraws($addr) {

	$db = db_get_db();

	$res = $db->query("select * from withdraws where addr = '$addr'");

	$ret = array();

	if (!$res)
		return $ret;

	while ($row = $res->fetch_assoc()) {

		array_push($ret, $row);
	};

	return $ret;
};

function db_add_withdraw($addr, $amnt, $stack) {

	// todo: make atomic
};

function db_get_stack($addr, $uuid, $inc_downloads = true) {

	$db = db_get_db();
	$res = $db->query("select stack from withdraws where addr = '$addr'") and uuid = '$uuid'");
	if ((!$res) || $res->num_rows == 0) return null;

	$ret = $res->fetch_row()[0];

	if ($mark_as_downloaded) {
		$db->query("update withdraw set dls = dls+1 where uuid = '$uuid'");
	};

	return $ret;
};


?>
