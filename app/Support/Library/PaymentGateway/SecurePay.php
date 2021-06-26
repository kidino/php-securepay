<?php

namespace App\Support\Library\PaymentGateway;

use Illuminate\Http\Request;

class SecurePay
{
    private $uid;
    private $auth_token;
    private $checksum_token;
    private $url = '';

    function __construct()
    {
        $this->url = 'https://' . (config('services.securepay.env') == 'sandbox' ? 'sandbox.' : '') . 'securepay.my/api/v1/payments';
        $this->uid = config('services.securepay.uid');
        $this->auth_token = config('services.securepay.auth_token');
        $this->checksum_token = config('services.securepay.checksum_token');
    }

    public function process($payload) 
    {
        $product_description = 'Payment for JomLaunch Order #'.$payload['order_number'];

        $string = $payload['buyer_email']."|".
            $payload['buyer_name']."|".
            $payload['buyer_phone']."|".
            $payload['callback_url']."|".
            $payload['order_number']."|".
            $product_description."|".
            $payload['redirect_url'] ."|".
            $payload['transaction_amount']."|".
            $this->uid;

        $sign = hash_hmac('sha256', $string, $this->checksum_token);

        $post_data = "buyer_name=".urlencode($payload['buyer_name'])."&token=". urlencode($this->auth_token) 
        ."&callback_url=".urlencode($payload['callback_url'])."&redirect_url=". urlencode($payload['redirect_url']) . 
        "&order_number=".urlencode($payload['order_number'])."&buyer_email=".urlencode($payload['buyer_email']).
        "&buyer_phone=".urlencode($payload['buyer_phone'])."&transaction_amount=".urlencode($payload['transaction_amount']).
        "&product_description=".urlencode($product_description)."&redirect_post=".urlencode('true').
        "&checksum=".urlencode($sign);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        
        $output = curl_exec($ch);        
        echo $output;
    }

    public function verify(Request $request)
    {

        $data = $request->all();
        ksort($data);

        $checksum = $data['checksum'];
        unset($data['checksum']);

        $string = implode('|', $data);

        $sign = hash_hmac('sha256', $string, $this->checksum_token);

        return ($sign == $checksum);
    }
}
