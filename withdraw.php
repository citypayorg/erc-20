<?php

require_once("ecr20/config.php");
?>




<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- CSS only -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
         integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
      <link href="./css/style.css" rel="stylesheet" />
      <title>CloudCoin Etherium to CloudCoin</title>
   </head>
   <body>
      <div class="bg-img1" style="height:100vh">
         <div class="container p-4">
            <div class="row mt-4">
               <div class="offset-md-1 col-md-2 pluses">
                  <img src="./images/logo.png" width="150" alt="logo"/>
                  <p class="text-light font-weight-light auth-service">Withdraw <br />CloudCoins <span class="text-white"></span></p>
               </div>
               <div class="col-md-8">
                  <p class="imp-notice"><span class="text-white-50">PURPOSE:</span> 
                  Use this form to transfer CloudCoin Ethernet Tokens into real CloudCoins that are in your Skywallet. 
                 Before this will work, you will need to install the 
                  MetaMask Browser plugin. 
                  including Bitcoin.com. 
                  </p>
               </div>
            </div>
            <div class="row mt-5">
               <div class="col-md-12 text-center" >
                
               </div>
               <div class="col-md-12">
                  <div>
                        <img src="images/jpeg25.jpg" alt="" class="img-fluid"   style="box-shadow: 5px 5px 10px #000000; border-radius: 2%;"  />
                      
                   
                     
                     
                     <div class="bg-space">
                        <div class="amount-card mt-5 shadow-sm p-3 mb-3 bg-white rounded">
                            <div class="bg-space">
                         
                        <h4 class="text-center">1 CCE = 1 CC</h4>
                        <h5 class="text-center text-black-50">Convert CloudCoin Etherium Tokens to CloudCoin</h5> 
                        
                     
                        
                     </div>
                        <h4  class="error text-center" id="errtxt" style="color:red" ><?php echo $error ?>&nbsp;&nbsp;</h4>  
                            
                           <form class="form-inline">
                           
<label class="my-1 mr-2" for="qty">Qty. CCE to Convert</label>
                           
    <input name="amount" class="custom-select my-1 mr-sm-2" type="number" step="100" min="100" max="50000" value="100">
                           

<label class="my-1 mr-2" for="skywallet">Skywallet Address</label> 

    <input name="skywallet" class="custom-select my-1 mr-sm-2" type="text" size="42" >&nbsp;&nbsp;
                   
                              
<div class="col-auto my-1 text-right">
                                 

    <button id="submit2" type="button" class="btn btn-primary mb-2 bg-paypal-btn">Convert</button>
                             
                              
                              </div>
                           
                           
                           </form>
                     
                           
                        </div>
                     </div>
                     </div>
        
               </div>
               <!-- END Money Order -->
                  
            </div>
                <p class="text-center" style="color:white">Support Phone: +1 (530) 562-4608</p>&nbsp;&nbsp;&nbsp;
       <p class="text-center"  style="color:white">Support Email: Support@CloudCoin.Global</p>
         </div>
         

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>




<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
	


<script type="text/javascript">
$(document).ready(function() {




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

	$("#submit2").click(function() {
		let val = $("input[name=skywallet]").val()
		let amount = $("input[name=amount]").val()
		if (!/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/.test(val)) {
			$("#errtxt").html("Invalid Skywallet address")
			return
		}
		if (!/^\d+$/.test(amount)) {
			$("#errtxt").html("Invalid amount")
			return
		}

		if (typeof(window.web3) == 'undefined') {
			$("#errtxt").html("Metamask not found. Please intall it first")
			return
		}

		if (typeof(window.ethereum) == 'undefined') {
			$("#errtxt").html("Metamask not found. Please intall it first")
			return
		}

		window.ethereum.enable()
		if (web3.eth.accounts.length == 0) {
			$("#errtxt").html("No accounts found in Metamask. You need to create at least one")
			return
		}
		const msgParams = [{
			type: 'string',      
			name: 'Message',     
			value: web3.eth.accounts[0] + ":" + val
		}]   

			
		web3.currentProvider.sendAsync({
			 method: 'eth_signTypedData',
		    	 params: [msgParams, web3.eth.accounts[0]],
			 from: web3.eth.accounts[0],
		}, function (err, result) {
			if (err || result.error) {
				$("#errtxt").html("Failed to sign message")
				return
			}
	
			let sig = result.result

			let contract = web3.eth.contract(abi).at(contractAddress)
			let decimals = contract.decimals(function (err, res) {
				if (err != null) {
					$("#errtxt").html("Failed to get decimals from contract")
					return
				}

				let decimals = res.c[0]
				console.log(decimals)
				amount = parseInt(amount, 10)
				famount = amount * (10 ** decimals)
					
				console.log("Sending " + famount)
				let getData = contract.burn.getData([famount]);
				let r = web3.eth.sendTransaction({to:contractAddress, from:web3.eth.accounts[0], data: getData}, function (err, res) {
					if (err != null) {
						$("#errtxt").html("Failed to send transaction: " + err.message)
						return
					}

					let txId = res
					console.log("transactionId " + txId)
					document.location = "/eth/ecr20/actionwithdraw.php?tx=" + txId + "&skywallet=" + val + "&sig=" + sig
				})

			})
		})
	})

})
</script>


</body>
</html>







<?php

?>
