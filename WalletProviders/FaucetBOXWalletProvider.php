<?php

class FaucetBOXWalletProvider extends WalletProvider
{
    protected $faucetbox = NULL;

    public function __construct()
    {
        global $faucetBoxKey;
        $this->faucetbox = new FaucetBOX($faucetBoxKey);
    }

    public function getBalance()
    {
        $result = $this->faucetbox->getBalance();
        if (array_key_exists("status", $result) && $result["status"] == 200) {
            return $result["balance"];
        }
        throw new Exception($result["message"], $result["status"]);
    }

    public function sendMoney($address, $balance)
    {
        global $cashoutMessage;
        $response = $this->faucetbox->send($address, $balance, $cashoutMessage);
        if ($response["success"]) {
            return $response["message"];
        }
        if ($response["message"] == "Insufficient funds.") {
                throw new NoCashException("Insufficient funds", 0);
        }
        throw new Exception($response["message"], 0);
    }
}
