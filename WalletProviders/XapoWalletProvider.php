<?php

class XapoWalletProvider extends WalletProvider
{
    protected $xapo = NULL;

    public function __construct()
    {
        global $appId, $appSecret;
        $this->xapo = new XapoCreditAPI("https://api.xapo.com/v1", $appId, $appSecret);
    }

    public function getBalance()
    {
        return SUB_UNIT;
//        return $this->xapo->getBalance() * SUB_UNIT;
    }

    public function sendMoney($address, $balance)
    {
        global $cashoutMessage;
        $response = $this->xapo->credit($address, "SAT", uniqid(), $balance, $cashoutMessage);
        if ($response->success) {
            return $response->message;
        }
        if ($response->code == "InsufficientFunds") {
                throw new NoCashException("Insufficient funds", 0);
        }
        throw new Exception($response->message, 0);
    }
}
