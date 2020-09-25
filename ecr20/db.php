<?php


class DB {
	var $conn;
	function __construct($host, $name, $user, $pass) {
		$this->conn = new mysqli($host, $user, $pass, $name);
		if ($this->conn->connect_error)
			die("Failed to connect to the Database");

		$this->conn->autocommit(TRUE);
	}

	function close() {
		$this->conn->close();
	}

	function putRecord($guid, $amount, $skywallet, $ethAccount, $dir, $txId, $sig) {
		$ts = time();

		if ($guid == "")
			$guidStr = "NULL";
		else 
			$guidStr = "'$guid'";

		if ($txId == "")
			$txStr = "NULL";
		else 
			$txStr = "'$txId'";

		$sql = "INSERT INTO transactions(guid, status, created_ts, updated_ts, amount, skywallet, ethaccount, dir, ethtxid, signature) VALUES($guidStr, ". TR_STATUS_NEW . ", $ts, $ts, $amount, '" . $skywallet . "', '". $ethAccount . "', " . $dir . ", ". $txStr .", '$sig');";
		if (mysqli_query($this->conn, $sql)) {
			return $this->conn->insert_id;
		}

		$this->close();

		return false;
	}

	function getNewTransactions() {
		$sql = "SELECT * FROM transactions WHERE status = " . TR_STATUS_NEW . " AND dir = " . DIR_CC;
		$result = $this->conn->query($sql);
		if ($result->num_rows <= 0)
			return [];

		$rv = [];
		while ($row = $result->fetch_assoc()) {
			$rv[] = $row;
		}
		
		return $rv;
	}

	function getTransaction($id) {
		$sql = "SELECT id, ethaccount, amount, signature from transactions WHERE status = " . TR_STATUS_SENT . " AND id=$id";
		$result = $this->conn->query($sql);

		if ($result->num_rows != 1)
			return 0;

		$row = $result->fetch_assoc();

		return $row;
	}

	function getVerifiedTransactions() {
		$sql = "SELECT * FROM transactions WHERE status = " . TR_STATUS_VERIFIED . " AND dir = " . DIR_CC;
		$result = $this->conn->query($sql);
		if ($result->num_rows <= 0)
			return [];

		$rv = [];
		while ($row = $result->fetch_assoc()) {
			$rv[] = $row;
		}
		
		return $rv;
	}

	function updateTransactionStatus($id, $status) {
		$ts = time();
		$sql = "UPDATE transactions SET status=$status, updated_ts=$ts WHERE id=$id";
		if (mysqli_query($this->conn, $sql))
			return true;

		return false;
	}

	function setTransactionTrx($id, $ethTrxId) {
		$ts = time();
		$sql = "UPDATE transactions SET status=" . TR_STATUS_SENT . ", updated_ts=$ts, ethtxid='$ethTrxId' WHERE id=$id";
		if (mysqli_query($this->conn, $sql))
			return true;

		return false;
	}
	function setTransactionSignature($id, $signature) {
		$ts = time();
		$sql = "UPDATE transactions SET status=" . TR_STATUS_SENT . ", updated_ts=$ts, signature='$signature' WHERE id=$id";
		if (mysqli_query($this->conn, $sql))
			return true;

		return false;
	}
	

	function getNewEthTransactions() {
		$sql = "SELECT * FROM transactions WHERE status = " . TR_STATUS_NEW . " AND dir = " . DIR_ETH;
		$result = $this->conn->query($sql);
		if ($result->num_rows <= 0)
			return [];

		$rv = [];
		while ($row = $result->fetch_assoc()) {
			$rv[] = $row;
		}

		return $rv;
	}

	function updateTransactionStatusAndAmount($id, $status, $amount, $from) {
		$ts = time();
		$sql = "UPDATE transactions SET status=$status, updated_ts=$ts, amount=$amount, ethaccount='$from' WHERE id=$id";
		if (mysqli_query($this->conn, $sql))
			return true;

		return false;
	}

	function getVerifiedEthTransactions() {
		$sql = "SELECT * FROM transactions WHERE status = " . TR_STATUS_VERIFIED . " AND dir = " . DIR_ETH;
		$result = $this->conn->query($sql);
		if ($result->num_rows <= 0)
			return [];

		$rv = [];
		while ($row = $result->fetch_assoc()) {
			$rv[] = $row;
		}
		
		return $rv;
	}

}

