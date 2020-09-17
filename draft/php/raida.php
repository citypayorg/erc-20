<?php

function raida_check_deposit($uuid, $amount) {

	// TODO call raida_go to check if the deposit is valid and with the right amount

	return true;
};

function raida_add_withdraw($amount) {

	$id = db_new_id();

	// TODO call raida_go to create a new withdraw of $amount and save it to a file (use $id ad unique id for filename)


	// returns false if unexpected error, like connection failure or insufficient funds in skywallet, which would be bad
	return true;
};



?>
