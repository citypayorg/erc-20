Cloudcoin to ERC20 token bridge
-------------------------------

General Descripion
------------------

Consists of 3 parts:

- A server that keeps balances of Cloudcoin for users
- A web client that allows the user to withdraw and deposit coins and eth tokens
- A smart contract for the ERC20 token

The server acts as a "hub" where the user can deposit "real" Cloudcoin, or transfer their ERC20 tokens. These 2 operations add to the user's balance.
With that balance, the user can request to withdraw real Cloudcoin, or to send their balance to ethereum as ERC20 tokens, both operations subtract from their balance.
The server has a Skywallet account where it keeps all the coins deposited, and it holds the pvivate key for the ethereum wallet that owns the ERC20 contract.

Files
-----

 - contracts/eCLD.sol

	This is the smart contract that runs on the Ethereum blockchain. It's a burnable ERC20 token, with 2 special methods:
	- mint(recipient, amount)
		Creates tokens with for given amount, and gives them to recipient address. *can only be called by the owner of the contract*
	- mont_with_receipt(recipient, amount, uuid, signature)
		Creates tokens and sends them to recipient. Expects a signed message from the owner of the contract authorising the mint.

	Other methods of note:
	- burn(amount) (from ERC20Burnable)
		Burns amount of tokens, and emits an event to the blockchain. This event can be examined by us to verify the coins were burned.
		A burn event is equivalent to sending the coins to the server, to be eventually withdrawn


 - php/

	This directory contains the PHP files for the server
	- services.php
		Contains the main API for the server, includes the following operations:
		- get_balance: provides the balance of a user
		- auth: allows the client to authenticate their wallet with the server. Some operations require this, some don't
		- auth_refresh: allows the client to refresh their authetication token
		- add_deposit: called by the client (probably via the SkyWallet action) when a new deposit of Cloudcoins is made, adds balance to the user
		- mint: creates a new "receipt", which can be used in ethereum to get tokens. Subtracts from the user's balance
		- get_receipt: allows clients to retrieve the receipt to use in ethereum to get their tokens
		- get_receipts: allows client to retrieve all the receipts for the user
		- receipt_set_status: allow clients to update the status of the receipt, status can be sent or confirmed, and clients can add the transaction id
		- add_burn: used by the client to notify that it burned coins. The server verifies the transaction and increases the balance of the user
		- add_withdraw: used to request a withdraw of Cloudcoins from the server. If the withdraw is in stack form, the server generates a stack file for the withdraw
		- get_withdraw: retrieves all the withdraws
		- get_stack: allows the client to download the stack file generated by a withdraw

	- eth.php
		Contains the ethereum-related operations
		- eth_verify_wallet: verifies if a wallet address is valid
		- eth_create_receipt: creates and signs a receipt to be used in the ethereum transaction to mint tokens
		- eth_check_burn: verifies that the user burned the coins claimed in "add_burn" service

	- raida.php
		Contains the RAIDA related operations
		- raida_check_deposit: checks that a deposit is valid in the sky wallet
		- raida_add_withdraw: withdraws from the sky wallet and creates a stack file with the coins
		- raida_skywallet_transfer: (not made) makes a skywallet transfer in case the user requests to withdraw via skywallet

	- db_mysql.php
		Implements the necessary datbase operations
