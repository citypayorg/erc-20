# erc-20
Allows us to go from CloudCoin to erc-20 and back


## Installation

1. Create MySQL database 'tokens'

```sql
CREATE DATABASE tokens
```

2. Import schema
```
$ mysql tokens < schema.sql
```

3. Put raida_go program in any folder and remember the path

4. Install Geth

https://geth.ethereum.org/downloads/

5. Launch Geth

./geth --http --rpcapi="db,eth,net,web3,personal,web3"

6. Wait until BlockChain synchronizes

7. Install the program

$ composer install


## Setup

The program consists of two entry points:

1) Action.php is supposed to be called by POSJS library

Pos JS setup example:
```js
var pos = new POSJS({
	'action': 'http://yourdomain.com/ecr20/action.php', 
	'merchant_skywallet' : 'my.skywallet.cc' 
})

```

2) The cron.php needs to be put into the crontab 

```
*/1 * * * * php -f /path/to/cron.php
```

Edit config.php file and change these parameters:

```php
// Database connection
define('DB_HOST', 'localhost');
define('DB_NAME', 'tokens');
define('DB_USER', 'root');
define('DB_PASS', '');

// Path to raida_go program
define('RAIDA_GO_PATH', '/var/www/cc/ecr20/raida_go');

// ECR-20 Parameters
define('ETH_CONTRACT', '0x2Fc7bac1f7139433079827226cF4a8BB464f6905');
define('ETH_ADDRESS', '0x5A2C2C6a3aDdD95be292D28F0FC266f1Ea4A4485');
define('ETH_SECRET', 'secret');
```

The file in the sol directory needs to be compiled 
(e.g. https://remix.ethereum.org/)

and put on the Eth BlockChain
