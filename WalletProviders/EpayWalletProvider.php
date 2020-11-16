<?php

class EpayWalletProvider extends WalletProvider
{
    protected $epay = NULL;

    public function __construct()
    {
        $this->epay = new SoapClient('http://api.epay.info/?wsdl');
    }

    public function getBalance()
    {
        global $epayApiKey;
        return $this->epay->f_balance($epayApiKey, 1);
    }

    public function sendMoney($address, $balance)
    {
        global $cashoutMessage, $epayApiKey;
        $response = $this->epay->send($epayApiKey, $address, $balance, 1, $cashoutMessage);
        if ($response["status"] > 0) {
            return $response["status"];
        }
        if ($response["status"] == -3) {
                throw new NoCashException("Insufficient funds", 0);
        }
        if ($response["status"] == -2) {
                throw new Exception("Wrong api key", 0);
        }
        if ($response["status"] == -5) {
                throw new Exception("You have to wait until ".date(DATE_RFC2822, $response["time"]), 0);
        }
        if ($response["status"] == -10) {
                throw new Exception("Daily budget reached, try again later", 0);
        }
        if ($response["status"] == -11) {
                throw new Exception("Time-frame limit reached, try again later", 0);
        }
        throw new Exception("Error: " . $response["status"], 0);
    }
}
