<?php
error_reporting(0);

// Database Config
// You will need to import the SQL file (structure.sql) first and create a MySQL database and user
$sqlHost = "localhost"; //Unless your MySQL server is on another server, leave it as localhost
$sqlUser = "faucet"; //Your MySQL username with permissions to modify the new database you created
$sqlPassword = "faucet"; //Your MySQL password
$sqlDatabase = "sql"; //The MySQL database you created and imported

$mysqli = new mysqli($sqlHost, $sqlUser, $sqlPassword, $sqlDatabase);
if($mysqli->connect_errno){
	echo "SQL error: " . $mysqli->connect_error;
	exit;
}

// Site Config
$siteName = "MiniFaucet";
// Coin type
// Valid types are:
// - BTC: bitcoin
// - LTC: litecoin
// - NMC: namecoin
// - BTCTEST: bitcoin testnet
// - NVC: novacoin
// - PPC: peercoin
// - DOGE: dogecoin
// - WDC: worldcoin
// - MOON: mooncoin
$coinType = "BTC";

// Array of 8 rewards in satoshis. 100,000,000 satoshis = 1 BTC
// You can set the weight: more weight => more odds
// 1 mBTC = 100,000 Satoshis
// 1 μBTC (microbitcoin) = 100 Satoshis
$rewards = array('1000' => 1, '1500' => 1, '2000' => 1, '2500' => 1, '3000' => 1, '3500' => 1, '4000' => 1, '5000' => 1);
$minReward = min(isAssoc($rewards) ? array_keys($rewards) : $rewards);
$maxReward = max(isAssoc($rewards) ? array_keys($rewards) : $rewards);

$dispenseTime = 3600; // how long per dispense (in seconds)
$dispenseTimeText = relative_time(time() + $dispenseTime);

// Having time issues? Your MySQL time zone is different from your PHP time zone.
// Contact your web hosting provider in that case.

$cashout = 20000; //min cashout. must be at least 10,000 satoshi
$cashoutMessage = "Cashout from $siteName - thanks for using!"; // note sent with cash out

// Security code for admin page
$adminSeccode = "";

// transaction fee in satoshis
$fee = 20000;

// Allowed address types
$allowEmail = false; // allow coinbase email addresses (we can't verify that it is exists
$allowCoin = true; // allow bitcoin addresses

// MiniFaucet automatic detect which online wallet do you use:
// If Coinbase api key is set then the site will use that
// If you set the Blockchain's guid, then that will be used

// Coinbase Account
// You need to make a NEW Coinbase account and generate an API key in the security tab
$apiKey = "";
$apiSecret = "";

// Blockchain Account
// Enable double-encryption on your wallet. guid is your MyWallet identifier.
//
// If you will be accessing the API from a server with a static ip address is recommended you
// enable the IP Lock found in the Security Section of your My Wallet account
$guid = "";
$firstpassword = "";
$secondpassword = "";

// ePay.info Account
$epayApiKey = "";

// Xapo Account
$appId = "";
$appSecret = "";

// Block.io wallet
$blockIoApiKey = "";
$blockIoPin = "";

// FaucetBOX Account
$faucetBoxKey = "";

// bitcoind Account
// If you run a bitcoind, enable the JSON-RPC commands
$rpchost = "";
$rpcssl = false;
$rpcport = 8332;
$rpcuser = "";
$rpcpassword = "";

// Make sure you have added balance to it!

$referPercent = 15; //referral percentage

$forcewait = 5; //seconds a user is forced to wait before cashing out.

// Google Analytics
// Place your Web Property ID for the Google Web Property you wish to track. (eg: UA-XXXX-Y)
// More info: https://developers.google.com/analytics/devguides/collection/analyticsjs/

$googleAnalyticsId = "";

// Recaptcha API keys
// You need GET YOUR OWN. Here https://www.google.com/recaptcha/admin/create

$recaptchaPub = " PUBLIC KEY";
$recaptchaPrv = " PRIVATE KEY";
$recaptchaVersion = 1;

$links = "<a href='example.org' target='_blank'>Adds links to your favorite Bitcoin faucets here</a><br /><a href='example.org' target='_blank'>Adds links to your favorite Bitcoin faucets here</a><br />";

// Advertisement Codes
// All ads rotate. There are 3 types: square, text, and banner. Add HTML code to the array

$squareAds = array('<iframe scrolling="no" style="border: 0; width: 250px; height: 250px;" src="http://coinurl.com/get.php?id=18045"></iframe>');

$textAds = array('<p><strong><a href="http://coinchat.org.">CoinChat</a></strong> - get free Bitcoins chatting!</p>', '<p><strong>Get your own faucet!</strong> <a href="https://gitlab.com/minifaucet/public">MiniFaucet PHP script - free!</a></p>');

$bannerAds = array('<iframe scrolling="no" style="border: 0; width: 468px; height: 60px;" src="http://coinurl.com/get.php?id=18056"></iframe>');
