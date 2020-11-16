<?php

class CoinbaseWalletProvider extends WalletProvider
{
    protected $coinbase = NULL;

    public function __construct()
    {
        global $apiKey, $apiSecret;
        if (!empty($apiSecret))
            $this->coinbase = Coinbase::withApiKey($apiKey, $apiSecret);
        else
            $this->coinbase = Coinbase::withSimpleApiKey($apiKey);
    }

    protected function handleException($e)
    {
        if ($e instanceof Coinbase_Exception) {
            error_log("sendMoney error: Coinbase API: ". $e->getResponse());
            $response = json_decode($e->getResponse());
            if (property_exists($response, "error")) {
                $response = $response->error;
            } else if (property_exists($response, "errors")) {
                $response = implode(", ", $response->errors);
            }
        } else {
            $response = $e->getMessage();
        }
        if (strpos($response, "You don't have that much") !== false) {
            throw new NoCashException($response, 0, $e);
        } else {
            throw new Exception($response, 0, $e);
        }
    }

    public function getBalance()
    {
        try {
            return $this->coinbase->getBalance() * SUB_UNIT;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function sendMoney($address, $balance)
    {
        global $cashoutMessage, $fee;
        $balance = $balance / SUB_UNIT;
        try {
            $response = $this->coinbase->sendMoney($address, sprintf("%.8f", $balance), $cashoutMessage, $fee > 0 ? ($fee / SUB_UNIT) : null);
            $response = $response->success ? $response->transaction->id : "error";
        } catch (Exception $e) {
            $this->handleException($e);
        }
        return $response;
    }
}
