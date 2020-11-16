<?php

abstract class WalletProvider {
    protected static $environment;

    public static function getInstance($refresh = false)
    {
        global $apiKey, $guid, $rpchost, $appId, $blockIoApiKey, $faucetBoxKey, $epayApiKey;
        if (is_null(self::$environment) || $refresh) {
            if (!empty($apiKey)) {
                self::$environment = new CoinbaseWalletProvider();
            } elseif (!empty($guid)) {
                self::$environment = new BlockchainWalletProvider();
            } elseif (!empty($appId)) {
                self::$environment = new XapoWalletProvider();
            } elseif (!empty($blockIoApiKey)) {
                self::$environment = new BlockIoWalletProvider();
            } elseif (!empty($epayApiKey)) {
                self::$environment = new EpayWalletProvider();
            } elseif (!empty($faucetBoxKey)) {
                self::$environment = new FaucetBOXWalletProvider();
            } elseif (!empty($rpchost)) {
                self::$environment = new BitcoindWalletProvider();
            } else {
                throw new Exception("The site doesnt set wallet provider");
            }
        }

        return self::$environment;
    }
    public abstract function getBalance();
    public abstract function sendMoney($address, $balance);
}
