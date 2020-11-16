<?php

class BitcoindWalletProvider extends WalletProvider
{
    protected $bitcoin;

    public function __construct()
    {
        global $rpchost, $rpcssl, $rpcport, $rpcuser, $rpcpassword;
        $this->bitcoin = new jsonRPCClient(sprintf('http%s://%s:%s@%s:%d/', $rpcssl ? "s" : "", $rpcuser, $rpcpassword, $rpchost, $rpcport));
    }

    public function getBalance()
    {
        return $this->bitcoin->getbalance() * SUB_UNIT;
    }

    public function sendMoney($address, $balance)
    {
        global $cashoutMessage, $fee;
        $balance = $balance / SUB_UNIT;
        try {
            if ($fee > 0) {
                $this->bitcoin->settxfee(round($fee / SUB_UNIT, 8));
            }
            $response = $this->bitcoin->sendtoaddress($address, $balance, $cashoutMessage);
        } catch (Exception $e) {
            $response = $e->getMessage();
            if (strpos($response, "Insufficient funds") !== false) {
                throw new NoCashException($response, 0, $e);
            } else {
                throw new Exception($response, 0, $e);
            }
        }
        return $response;
    }
}
