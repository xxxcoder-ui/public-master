<?php

class BlockchainWalletProvider extends WalletProvider
{
    protected $blockchain;

    public function __construct()
    {
        global $guid, $firstpassword;
        $this->blockchain = new \Blockchain\Blockchain();
        $this->blockchain->Wallet->credentials($guid, $firstpassword);
    }

    public function getBalance()
    {
        return $this->blockchain->Wallet->getBalance()  * SUB_UNIT;
    }

    public function sendMoney($address, $balance)
    {
        global $guid, $firstpassword, $secondpassword, $cashoutMessage, $fee;
        $this->blockchain->Wallet->credentials($guid, $firstpassword, $secondpassword);
        try
        {
            $response = $this->blockchain->Wallet->send($address, $balance / SUB_UNIT, null, $fee >= 50000 ? ($fee / SUB_UNIT) : null, $cashoutMessage);
        } catch(Exception $e) {
            if ($e->getMessage() == 'No free outputs to spend') {
                throw new NoCashException();
            }
            throw $e;
        }
        return $response;
    }
}
