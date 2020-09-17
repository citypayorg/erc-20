pragma solidity ^0.6.0;

import "./Context.sol";
import "./ERC20.sol";
import "./ERC20Burnable.sol";



contract eCLD is ERC20Burnable {

    address private _owner;
    mapping (bytes32 => bool) private _receipts;

    function _check_signature(bytes32 hash, bytes32 signature) internal {
     
        // I don't fully understand this
        //require(_owner == ecrecover(hash, signature), "Signature invalid");
    }


    function mint_with_receipt(address recipient, uint256 amount, uint256 uuid, bytes32 signature) public {

        bytes32 hash = sha256(abi.encodePacked(recipient, amount, uuid));
        require(!_receipts[hash], "Receipt already used");
        
        _check_signature(hash, signature);
        
        _mint(recipient, amount);
        _receipts[hash] = true;
    }
    
    function mint(address recipient, uint256 amount) public {
        
        require(_owner == _msgSender());
        
        _mint(recipient, amount);
    }


    constructor() ERC20("Ethereum CloudCoin", "eCLD") public {
        
        _owner = _msgSender();
    }

}

