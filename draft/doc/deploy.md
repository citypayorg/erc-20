Contract deployment
-------------------

This document describes the workflow to deploy the smart contract to the blockchain. Some of the procedures are done manually, the reason for this is mostly
because the contract is very simple. Other IDEs could be used in the future to automate some aspects of this, but since there's only 1 contract to deploy, and
it'll be deployed only once, this procedure works for now.


Metamask
--------
* Get metamask for your browser.

* Create a wallet. This wallet will be the owner of the contract, and its private key can be used to create tokens in the contract, so it must be kept safe.
  * Make sure you can extract/backup the private key, which we'll need to install in the server.
	* On metamask, go to the menu of the top left (icon looks like 3 dots arranged vertically) and select "Account Details"
	* In the dialog that appears, press the button "Export private key". It'll ask for your wallet password.
	* Copy the key provided, keep it safe


Remix IDE
---------

* On a browser go to remix.ethereum.org

* On the left you'll see a list of files, like "1_Storage.sol", "2_Owner.sol", etc. Right click and delete each file, including the one inside "tests".
* Click on the folder icon to add files from your filesystem to the project. Add all the .sol files from the contract/ directory (should be 7 files)
* Select the file "eCLD.sol", you'll see the code in the center panel.
* On the left most of the screen there's a series of vertical icons, click the 2nd one, with the tooltip "Solidity compiler"
* Click the button "Compile eCLD.sol". A green checkmark should appear on the left icon for the compiler section.
* Click on the third icon on the left, for the section "Deploy & Run transactions".
* On the "Environment" drop down, select "injected web3".
* The metamask window should pop up, asking you to connect the wallet, accept the connection.
  * Make sure your Metamask is connected to the right network, use "ropsten test network" for testing, "Main network" for deploying. This can be seen at the top center of the metamask window
  * you will see the name of the network below the Environment drop down
* On the "Contract" dropdown, select "eCLD - browser/eCLD.sol" (this is important, they'll be a bunch of options that look similar, you want the one with 'eCLD' at the start
* A metamask window will popup, asking to confirm the transaction. Press Confirm
* On the bottom panel on the browser you'll see a console with some output. It'll take a while for the transaction to confirm.
  * you'll see a Metamask notification when the confirmation is done
* After the transaction confirms, on the left panel at the bottom you'll see "Deployed Contracts", and a square with the text "ECLD AT 0x...", with a "copy" icon.
* Press the copy icon to copy the address of the contract to the clipboard. This is the address of the contract, paste it somewhere safe.

Server
------
On our server, take the contract address and the private key obtained in the first section, and install those where they are needed.


Notes
-----

* Remember Ethereum contracts are permanent, we can't update the code, and we must only deploy once
* After deploying, it might be wise to remove the wallet from your metamask. The private key must be kept somewhere safe, and preferably off-line. It'll also be in the node used by our server.

Send questions to amanzur@protonmail.com



