<?php

require_once("ecr20/config.php");

$id = intval($_GET['trid']);
if ($id <= 0)
	die();

?>








<?php include  "top.php"; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
	<div class="content faq">
		<div class="contentfluid narrow">

		<?php
		?>

		<h1>CloudCoins have been sent. Please wait...</h1>

		<div class="infotrade">
		It may take up to two minutes to complete the transaction
		</div>

			<div class="inner">

				<div class="error" id="errtxt">
<?php echo $error ?>&nbsp;
				</div>
				<div id="dots" style="font-size:  4rem"></div>
			</div>



		


		</div>
	</div>

<?php include "footer.php" ?>


<script type="text/javascript">
let i = 1
function doPoll() {
	$.post('/eth/ecr20/trstatus.php?id=' + <?=$id?>, function(data) {
		if (i > 20)
			return

		i++
		let dots = ""
		for (let d = 0; d < i; d++)
			dots += "."

		$("#dots").html(dots)

		let o 
		try {
			o = JSON.parse(data)	
		} catch (e) {
			setTimeout(doPoll, 10000)
			return
		}

		if (!('signature' in o)) {
			setTimeout(doPoll, 10000)
			return
		}


		go(o)


	}).fail(function () {
		console.log("fail")
		$("#errtxt").html("Failed to query remote server")
	})
}

$(document).ready(function() {
	doPoll()
})

function go(o) {
	let signature = o['signature']
	let r = signature.substring(2, 66)
	let s = signature.substring(66, 130)
	let v = signature.substring(130)

	console.log(signature)
	console.log(r)
	console.log(s)
	console.log(v)


//	let contractAddress = "0x24d569F5fF18775F0E807E5ff44CB51Da2dC91b4"
//	let contractAddress = "0x3361B37De1aC29F9efa19c71ad97B0b01B4494D7"
	let contractAddress = "<?=ETH_CONTRACT?>"
	let abi = [
	  {
	    "constant": true,
	    "inputs": [],
	    "name": "name",
	    "outputs": [
	      {
		"name": "",
		"type": "string"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  },
	  {
	    "constant": true,
	    "inputs": [],
	    "name": "symbol",
	    "outputs": [
	      {
		"name": "",
		"type": "string"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  },
	  {
	    "constant": true,
	    "inputs": [],
	    "name": "decimals",
	    "outputs": [
	      {
		"name": "",
		"type": "uint8"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  },
	  {
	    "constant": true,
	    "inputs": [],
	    "name": "totalSupply",
	    "outputs": [
	      {
		"name": "",
		"type": "uint256"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  },
	  {
	    "constant": true,
	    "inputs": [
	      {
		"name": "_owner",
		"type": "address"
	      }
	    ],
	    "name": "balanceOf",
	    "outputs": [
	      {
		"name": "balance",
		"type": "uint256"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  },

	  {
	    "constant": false,
	    "inputs": [
	      {
		"name": "_to",
		"type": "address"
	      },
	      {
		"name": "_value",
		"type": "uint256"
	      }
	    ],
	    "name": "transfer",
	    "outputs": [
	      {
		"name": "success",
		"type": "bool"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  },

	  {
	    "constant": false,
	    "inputs": [
	      {
		"name": "_to",
		"type": "address"
	      },
	      {
		"name": "_value",
		"type": "uint256"
	      }
	    ],
	    "name": "mint",
	    "outputs": [
	      {
		"name": "success",
		"type": "bool"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  },

          {
            "constant": false,
            "inputs": [
              {
                "name": "_to",
                "type": "address"
              },
              {
                "name": "_amount",
                "type": "uint256"
              },
              {
                "name": "_uuid",
                "type": "uint256"
              },
              {
                "name": "_v",
                "type": "uint8"
              },
              {
                "name": "_r",
                "type": "bytes32"
              },
              {
                "name": "_s",
                "type": "bytes32"
              },
		
            ],
            "name": "mint_with_receipt",
            "outputs": [
              {
                "name": "success",
                "type": "bool"
              }
            ],
            "payable": false,
            "type": "function"
          },


	  {
	    "constant": false,
	    "inputs": [
	      {
		"name": "_value",
		"type": "uint256"
	      }
	    ],
	    "name": "burn",
	    "outputs": [
	      {
		"name": "success",
		"type": "bool"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  },
	  {
	    "constant": false,
	    "inputs": [
	      {
		"name": "_from",
		"type": "address"
	      },
	      {
		"name": "_to",
		"type": "address"
	      },
	      {
		"name": "_value",
		"type": "uint256"
	      }
	    ],
	    "name": "transferFrom",
	    "outputs": [
	      {
		"name": "success",
		"type": "bool"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  },
	  {
	    "constant": false,
	    "inputs": [
	      {
		"name": "_spender",
		"type": "address"
	      },
	      {
		"name": "_value",
		"type": "uint256"
	      }
	    ],
	    "name": "approve",
	    "outputs": [
	      {
		"name": "success",
		"type": "bool"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  },
	  {
	    "constant": true,
	    "inputs": [
	      {
		"name": "_owner",
		"type": "address"
	      },
	      {
		"name": "_spender",
		"type": "address"
	      }
	    ],
	    "name": "allowance",
	    "outputs": [
	      {
		"name": "remaining",
		"type": "uint256"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  },
	  {
	    "anonymous": false,
	    "inputs": [
	      {
		"indexed": true,
		"name": "_from",
		"type": "address"
	      },
	      {
		"indexed": true,
		"name": "_to",
		"type": "address"
	      },
	      {
		"indexed": false,
		"name": "_value",
		"type": "uint256"
	      }
	    ],
	    "name": "Transfer",
	    "type": "event"
	  },
	  {
	    "anonymous": false,
	    "inputs": [
	      {
		"indexed": true,
		"name": "_owner",
		"type": "address"
	      },
	      {
		"indexed": true,
		"name": "_spender",
		"type": "address"
	      },
	      {
		"indexed": false,
		"name": "_value",
		"type": "uint256"
	      }
	    ],
	    "name": "Approval",
	    "type": "event"
	  },
	  {
	    "inputs": [
	      {
		"name": "_initialAmount",
		"type": "uint256"
	      },
	      {
		"name": "_tokenName",
		"type": "string"
	      },
	      {
		"name": "_decimalUnits",
		"type": "uint8"
	      },
	      {
		"name": "_tokenSymbol",
		"type": "string"
	      }
	    ],
	    "payable": false,
	    "type": "constructor"
	  },
	  {
	    "constant": false,
	    "inputs": [
	      {
		"name": "_spender",
		"type": "address"
	      },
	      {
		"name": "_value",
		"type": "uint256"
	      },
	      {
		"name": "_extraData",
		"type": "bytes"
	      }
	    ],
	    "name": "approveAndCall",
	    "outputs": [
	      {
		"name": "success",
		"type": "bool"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  },
	  {
	    "constant": true,
	    "inputs": [],
	    "name": "version",
	    "outputs": [
	      {
		"name": "",
		"type": "string"
	      }
	    ],
	    "payable": false,
	    "type": "function"
	  }
	]

	if (typeof(window.web3) == undefined) {
		$("#errtxt").html("Metamask not found. Please intall it first")
		return
	}

	if (typeof(window.ethereum) == undefined) {
		$("#errtxt").html("Metamask not found. Please intall it first")
		return
	}

	window.ethereum.enable()
	if (web3.eth.accounts.length == 0) {
		$("#errtxt").html("No accounts found in Metamask. You need to create at least one")
		return
	}
/*
	const msgParams = [{
		type: 'string',      
		name: 'Message',     
		value: web3.eth.accounts[0] + ":" + val
	}]   
	*/
			
	console.log(o)
	let contract = web3.eth.contract(abi).at(contractAddress)
	let decimals = contract.decimals(function (err, res) {
		if (err != null) {
			$("#errtxt").html("Failed to get decimals from contract")
			return
		}

		let decimals = res.c[0]
		console.log(decimals)
		amount = o['amount']
		amount = parseInt(amount, 10)
		famount = amount * (10 ** decimals)
			
		console.log(famount)
		dv = parseInt(v, 16);

		
		//let br = parseHexString(r)
		//let bs = parseHexString(s)

		
		//br = "0x074c71954ce8b9a0806d4e3fd298635185dab8b3c6f61c8d840057a75385706d";
		//bs = "0x526774a8a1b431ab23654b2c94521509640ce85e54c5f1bc7ba91230f07023a4";
		console.log("r="+r)
		console.log("s="+s)
		//let br = parseInt(r, 16)
		//let bs = parseInt(s, 16)

		br="0x"+r
		bs="0x"+s

		console.log("br="+br)
		console.log("bs="+bs)
/*
let sig="d2c6e445f02a7810b8b8fd856087f800ae9e828691549e5fba4d1a4325fefd0f149628dc4afe6098fc5060eb71faa35bfde02f1f878f4c6694debee5ce4581811b"		
var r = `0x${sig.slice(0, 64)}`
var s = `0x${sig.slice(64, 128)}`
//var v = web3.toDecimal(sig.slice(128, 130)) + 27

*/
console.log("x="+r+" s="+s+" v="+v)


		console.log(r)
		console.log(br)

		

		console.log("Sending " + famount + " dv="+dv)
		console.log(contract.mint_with_receipt)
		//let getData = contract.mint_with_receipt.getData(o['ethaccount'], famount, o['id'], dv, br, bs);
		let getData = contract.mint_with_receipt.getData(o['ethaccount'], famount, o['id'], dv, br, bs);
		console.log("gas")
		let rq = web3.eth.sendTransaction({to:contractAddress, from:web3.eth.accounts[0], data: getData, gas: 100000}, function (err, res) {
			if (err != null) {
				$("#errtxt").html("Failed to send transaction: " + err.message)
				return
			}

			let txId = res

			$("#errtxt").html("Transaction ID " + txId)
			console.log("transactionId " + txId)
			//document.location = "/ecr20/actionwithdraw.php?tx=" + txId + "&skywallet=" + val + "&sig=" + sig
		})

	})

}

function parseHexString(str) { 
    var result = [];
    while (str.length >= 2) { 
        result.push(parseInt(str.substring(0, 2), 16));

        str = str.substring(2, str.length);
    }

	console.log("xxx")
	let s = "";
	for (let i=0; i < result.length;i++) {
		console.log("i"+i + " adding " + String.fromCharCode(result[i]) + " code=" + String.fromCharCode(result[i]).charCodeAt(0).toString(16));
		s+= String.fromCharCode(result[i])
	}

	console.log(result)
	return s
    return result
}

function bin2String(array) {
  var result = "";
  for (var i = 0; i < array.length; i++) {
    result += String.fromCharCode(parseInt(array[i], 2));
  }
  return result;
}
</script>


</body>
</html>







<?php

?>
