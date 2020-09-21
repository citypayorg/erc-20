This document describes the worflow for using the "mint_with_receipt" method, which makes users pay for the gas fee required to mint the tokens.

Server
------
On the server, when a deposit arrives, the server creates a unique message. This can consist of the following:

* The receipient address for the tokens (this is the user's wallet address)
* The amount to be minted
* A unique id, can be a sequential number

All of these parameters are then hashed. The method for hashing them needs to be well know, as we need to reproduce this hash inside the smart contract.
The server then asks the Ethereum node to sign the message (the hash is the message), this will produce a set of values "v" "r" and "s". The values are
sent back to the web client, along with the hash and the values used to create the message. This is a "receipt".

Web Client
----------
On the web client, after the user deposits the coins and calls the server, the server replies with the receipt. The web client uses web3.js to call the
"mint_with_receipt" method:

```
function mint_with_receipt(address recipient, uint256 amount, uint256 uuid, uint8 v, bytes32 r, bytes32 s) public
```

Smart Contract
--------------
On the smart contract we receive the call to mint_with_receipt. The contract needs to re-create the hash, using the provided parameters, currently like this:

```
bytes32 hash = sha256(abi.encodePacked(recipient, amount, uuid));
```

With the hash, it can check that the receipt hasn't been used already. Then, it uses the hash and signature to verify the receipt's validity:

```
require(_owner == ecrecover(hash, v, r, s), "Signature invalid");
```

"_owner" is the owner of the smart contract, the only wallet that can sign a receipt. If the receipt is valid, a normal mint call happens, and the recipient
will receive their tokens.

Some notes
----------

The Solidity documentation explains this workflow here:

https://ethereum.stackexchange.com/questions/1777/workflow-on-signing-a-string-with-private-key-followed-by-signature-verificatio

It seems like some wallets (like Metamask) prepend the string "\x19Ethereum Signed Message:\n32", it's not clear if the nodes do this. In that case it might
be necessary to do this in the smart contract.
