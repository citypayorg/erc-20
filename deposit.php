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
      <title>CloudCoin to CloudCoin Etherium Token</title>
   </head>
   <body>
      <div class="bg-img1" style="height:100vh">
         <div class="container p-4">
            <div class="row mt-4">
               <div class="offset-md-1 col-md-2 pluses">
                  <img src="./images/logo.png" width="150" alt="logo"/>
                  <p class="text-light font-weight-light auth-service">Deposit <br /> CloudCoin <span class="text-white"></span></p>
               </div>
               <div class="col-md-8">
                  <p class="imp-notice"><span class="text-white-50">PURPOSE:</span> 
                  Use this form to transfer your real CloudCoins that are in your Skywallet to CloudCoin Etherium Token (CCE). 
                  The CCE can be traded on many major exchanges. Before this will work, you will need to install the 
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
                        <img src="images/jpeg250.jpg" alt="" class="img-fluid"   style="box-shadow: 5px 5px 10px #000000; border-radius: 2%;"  />
                      
                   
                     
                     
                     <div class="bg-space">
                        <div class="amount-card mt-5 shadow-sm p-3 mb-3 bg-white rounded">
                            <div class="bg-space">
                         
                        <h4 class="text-center">1 CC = 1 CCE</h4>
                        <h5 class="text-center text-black-50">Convert CloudCoins to CloudCoin Etherium Tokens</h5> 
                        
                     
                        
                     </div>
                        <h4  class="error text-center" id="errtxt" style="color:red" ><?php echo $error ?>&nbsp;&nbsp;</h4>  
                            
                           <form class="form-inline">
                           
<label class="my-1 mr-2" for="qty">Qty. CC to Convert</label>
                           
    <input name="amount" class="custom-select my-1 mr-sm-2" type="number" step="100" min="100" max="50000" value="100">
                           

<label class="my-1 mr-2" for="ecd20">Your ETH Address</label> 

    <input name="ecd20" class="custom-select my-1 mr-sm-2" type="text" size="42" >&nbsp;&nbsp;
                   
                              
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
         
         
    </body>
</html>




<style>
button.posButton {
	padding: 0;
	margin: 0;
	transition: none
}
button.posButton:hover {
	background: none;
	background-size: 110px; 
	background-position-y: -5px
}

input[type="text"], input[type="password"] {
margin: 0;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cloudcoin.global/assets/posjs.min.v003.js" type="text/javascript"></script>
<script type="text/javascript">
	var pos = new POSJS({
		'timeout':'20000', //20 seconds
		'action': 'http://raida.tech/eth/ecr20/action.php',
		'merchant_skywallet' : '<?=CC_WALLET?>' 
	})
</script>
	<div class="content faq">
		<div class="contentfluid narrow">

		<?php
		?>

	           

		</div>
	</div>



<script type="text/javascript">
$(document).ready(function() {
	$("#submit2").click(function() {
                if (typeof(window.web3) == 'undefined') {
                        $("#errtxt").html("Metamask not found. Please intall it first")
                        return
                }

                if (typeof(window.ethereum) == 'undefined') {
                        $("#errtxt").html("Metamask not found. Please intall it first")
                        return
                }

		console.log(window.web3.eth)

		let val = $("input[name=ecd20]").val()
		let amount = $("input[name=amount]").val()

		if (!/^0x[a-fA-F0-9]{40}$/.test(val)) {
			$("#errtxt").html("Invalid address")
			return
		}
		if (!/^\d+$/.test(amount)) {
			$("#errtxt").html("Invalid amount")
			return
		}
		window.ethereum.enable()
                if (web3.eth.accounts.length == 0) {
                        $("#errtxt").html("No accounts found in Metamask. You need to create at least one")
                        return
                }

		let account = web3.eth.accounts[0].toLowerCase()
		if (account != val.toLowerCase()) {
			$("#errtxt").html("Account doesn't exist or you have too many accounts")
			return

		}

		pos.show({'account':val, 'amount':amount})
		/*
		contract.balanceOf(account, function (err, balance) {
			if (err != null) {
	                        $("#errtxt").html("Failed to get balance")
        	                return
			}

			contract.decimals(function (err, decimals) {
				if (err != null) {
		                        $("#errtxt").html("Failed to get decimals")
        		                return
				}

				balance = balance.div(10**decimals).toString()
				if (balance < amount) {
		                        $("#errtxt").html("Insufficient funds")
        		                return
				}
			
				
			})


		})
		*/

	})

})
</script>


</body>
</html>


<?php

?>
