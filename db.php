<?php


class DB {
	var $conn;
	function __construct($host, $name, $user, $pass) {
		$this->conn = new mysqli($host, $user, $pass, $name);
		if ($this->conn->connect_error)
			die("Failed to connect to the Database");
	}

	function close() {
		$this->conn->close();
	}

	function putRecord($guid, $amount, $skywallet, $ethAccount) {
		$ts = time();
		$sql = "INSERT INTO transactions(guid, status, created_ts, updated_ts, amount, skywallet, ethaccount) VALUES('" . $guid . "', ". TR_STATUS_NEW . ", $ts, $ts, $amount, '" . $skywallet . "', '". $ethAccount . "');";
		if (mysqli_query($this->conn, $sql))
			return true;

		$this->close();

		return false;
	}

	function getNewTransactions() {
		$sql = "SELECT * FROM transactions WHERE status = " . TR_STATUS_NEW;
		$result = $this->conn->query($sql);
		if ($result->num_rows <= 0)
			return [];

		$rv = [];
		while ($row = $result->fetch_assoc()) {
			$rv[] = $row;
		}
		
		return $rv;
	}

	function getVerifiedTransactions() {
		$sql = "SELECT * FROM transactions WHERE status = " . TR_STATUS_VERIFIED;
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
	

}

