<?php

class BlockIoWalletProvider extends WalletProvider
{
    protected $blockio;

    public function __construct()
    {
        global $blockIoApiKey, $blockIoPin;
        $this->blockio = new BlockIo($blockIoApiKey, $blockIoPin, 2);
    }

    public function getBalance()
    {
        $result = $this->blockio->get_balance();
        return $result->{"data"}->{"available_balance"}  * SUB_UNIT;
    }

    public function sendMoney($address, $balance)
    {
        global $cashoutMessage, $fee;
        try
        {
            $response = $this->blockio->withdraw(array('amounts' => $balance / SUB_UNIT, 'to_addresses' => $address));
        } catch(Exception $e) {
            if (strncmp($e->getMessage(), 'Failed: Cannot withdraw funds without Network Fee', 49) == 0) {
                throw new NoCashException();
            }
            throw $e;
        }
        return $response;
    }
}
