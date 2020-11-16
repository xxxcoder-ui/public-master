<?php

if (version_compare(PHP_VERSION, '5.3.0') < 0) {
    exit("Sorry, this version of MiniFaucet will only run on PHP version 5.3 or greater!\n");
}

if (!file_exists("config.php")) {
    exit("You need to setup your MiniFaucet! Rename config.sample.php to config.php, and configure the settings\n");
}

function isAssoc($arr)
{
    return array_keys($arr) !== range(0, count($arr) - 1);
}

require_once 'config.php';
require_once 'coindata.php';
require_once 'recaptchalib.php';
require_once 'ReCaptcha/autoload.php';
require_once 'validator.php';
require_once 'Coinbase/Coinbase.php';
require_once 'Blockchain/Blockchain.php';
require_once 'BlockIo/lib/block_io.php';
require_once 'Xapo/XapoCreditAPI.php';
require_once 'faucetbox.php';
require_once 'jsonRPCClient.php';
require_once 'Slim/Slim.php';
require_once 'WalletProviders/WalletProvider.php';
require_once 'WalletProviders/BitcoindWalletProvider.php';
require_once 'WalletProviders/BlockchainWalletProvider.php';
require_once 'WalletProviders/BlockIoWalletProvider.php';
require_once 'WalletProviders/CoinbaseWalletProvider.php';
require_once 'WalletProviders/XapoWalletProvider.php';
require_once 'WalletProviders/EpayWalletProvider.php';
require_once 'WalletProviders/FaucetBOXWalletProvider.php';

define("COIN_NAME", getCoinName($coinType));
define("UP_COIN_NAME", ucfirst(COIN_NAME));
define("SUB_UNIT", getSubunitDivider($coinType));
define("SUB_UNIT_NAME", getSubunitName($coinType));

function urlFor($name, $params = array(), $appName = 'default')
{
    return \Slim\Slim::getInstance($appName)->urlFor($name, $params);
}

function getAd($arr, $location = null)
{
    if (!is_null($location)) {
        $div_pre = sprintf("<div id=\"%s\">", $location);
        $div_post = "</div>\n";
    } else {
        $div_pre = "";
        $div_post = "\n";
    }
    return $div_pre.(!empty($arr) ? $arr[rand(0, count($arr)-1)] : "banner here").$div_post;
}

function getAdDivId($location)
{
    return $location;
}

function relative_time($date)
{
    $diff = time() - $date;
    $poststr = $diff > 0 ? " ago" : "";
    $adiff = abs($diff);
    if ($adiff<60) {
        return $adiff . " second" . plural($adiff) . $poststr;
    }
    if ($adiff<3600) { // 60*60
            return round($adiff/60) . " minute" . plural($adiff) . $poststr;
    }
    if ($adiff<86400) { // 24*60*60
            return round($adiff/3600) . " hour" . plural($adiff) . $poststr;
    }
    if ($adiff<604800) { // 7*24*60*60
            return round($adiff/86400) . " day" . plural($adiff) . $poststr;
    }
    if ($adiff<2419200) { // 4*7*24*60*60
            return $adiff . " week" . plural($adiff) . $poststr;
    }
    return "on " . date("F j, Y", strtotime($date));
}

function plural($a)
{
        return ($a > 1 ? "s" : "");
}

function checkaddress($address)
{
    global $allowEmail, $allowCoin, $coinType;
    if ($allowCoin && determineValidity($address, $coinType)) {
        return true;
    }
    if ($allowEmail && (filter_var($address, FILTER_VALIDATE_EMAIL) !== false)) {
        return true;
    }
    return false;
}

function getIP()
{
    if (getenv("HTTP_CLIENT_IP")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } elseif (getenv("REMOTE_ADDR")) {
        $ip = getenv("REMOTE_ADDR");
    } else {
        $ip = "UNKNOWN";
    }
    return $ip;
}

function checkRecaptcha($request, $remoteIp)
{
    global $recaptchaPrv, $recaptchaVersion;
    if (isset($recaptchaVersion) && $recaptchaVersion == "funcaptcha") {
        $session_token = $request->post('fc-token');
        $fc_api_url = "https://funcaptcha.com/fc/v/?private_key=".$recaptchaPrv."&session_token=".$session_token."&simple_mode=1";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fc_api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output === "1";
    } elseif (isset($recaptchaVersion) && $recaptchaVersion == 2) {
        $recaptcha = new \ReCaptcha\ReCaptcha($recaptchaPrv, new \ReCaptcha\RequestMethod\Curl());
        $resp = $recaptcha->verify($request->post('g-recaptcha-response'), $remoteIp);
        return $resp->isSuccess();
    } else {
        $resp = recaptcha_check_answer($recaptchaPrv, $remoteIp,
            $request->post('recaptcha_challenge_field'), $request->post('recaptcha_response_field'));
        return $resp->is_valid;
    }
}

function getserverbalance($force = false)
{
    global $apiKey, $guid, $rpchost;
    if (!$force) {
        // we store the server balance in sql with a spec address called 'SERVERBALANCE'
        $balance_sql = "SELECT balance FROM balances WHERE email='SERVERBALANCE' ";
        $balance_sql .= "AND totalbalance > ".(time() - 1800).";";
        $balance_query = sql_query($balance_sql);
        if ($balance_query->num_rows) {
            $balance = fetch_one($balance_query);
            return $balance;
        }
    }
    try {
        try {
            $balance = WalletProvider::getInstance()->getBalance();
        } catch (Exception $e) {
            if ($e->getMessage() != "The site doesnt set wallet provider") throw $e;
            $balance = -1;
        }
        $date = time();
        $insert_sql = "INSERT INTO balances(balance, totalbalance, email, referredby) ";
        $insert_sql .= "VALUES($balance, '$date', 'SERVERBALANCE', 0) ON DUPLICATE KEY ";
        $insert_sql .= "UPDATE balance = $balance, totalbalance = '$date';";
        sql_query($insert_sql);
        return $balance;
    } catch (Exception $e) {
        return 0;
    }
}

class NoCashException extends Exception
{
}

function sendMoney($address, $balance)
{
    return WalletProvider::getInstance()->sendMoney($address, $balance);
}

function sql_query($sql)
{
    global $mysqli;
    return $mysqli->query($sql);
}

function fetch_row($query)
{
    return $query->fetch_row();
}

function fetch_assoc($query)
{
    return $query->fetch_assoc();
}

function fetch_all($query, $resulttype = MYSQLI_NUM)
{
    if (method_exists($query, 'fetch_all')) { # Compatibility layer with PHP < 5.3
        $res = $query->fetch_all($resulttype);
    } else {
        for ($res = array(); $tmp = $query->fetch_array($resulttype);) {
            $res[] = $tmp;
        }
    }
    return $res;
}

function fetch_one($query)
{
    $row = $query->fetch_row();
    return $row[0];
}
