<?php

require_once('../../config.php');
$api_key = $coinbase_api_key;
$server_name = $serverName;
$redirect_url = 'https://'.$_SERVER['SERVER_NAME'].'/account/donate.php?coinbase=successful';
$cancel_url = 'https://'.$_SERVER['SERVER_NAME'].'/account/donate.php?coinbase=failed';

if (isset($_POST['jid']) && isset($_POST['amount']) && isset($_POST['value'])){
    if (!empty($_POST['jid']) && !empty($_POST['amount']) && !empty($_POST['value'])){

        $jid = filter_var($_POST['jid'], FILTER_VALIDATE_INT);
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $currency = filter_var($_POST['currency'], FILTER_SANITIZE_STRING);
        $amount = filter_var($_POST['amount'], FILTER_VALIDATE_INT);
        $value = filter_var($_POST['value'], FILTER_VALIDATE_INT);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

        $post = array(
            "name" => $value.' '.$name,
            "description" => $server_name.' - '.$description.', User#'.$jid,
            "local_price" => array(
                'amount' => $amount,
                'currency' => $currency
            ),
            "pricing_type" => "fixed_price",
            "metadata" => array(
                'customer_jid' => $jid,
                'customer_value' => $value
            ),
            "redirect_url" => $redirect_url,
            "cancel_url" => $cancel_url,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.commerce.coinbase.com/charges');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'X-Cc-Api-Key: '.$api_key;
        $headers[] = 'X-Cc-Version: 2018-03-22';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response, true);

        if ($response['data']){
            $uqid = $response['data']['id'];
            $url = $response['data']['hosted_url'];
            $status = $response['data']['timeline'][0]['status'];

            print_r($jid.'<br>'.$uqid.'<br>'.$currency.'<br>'.$value.'<br>'.$status);

            $stmt = $conn->prepare("INSERT INTO [GB_JoymaxPortal].[dbo].[APH_PaypalIPN] ([JID], [PSPaymentDate], [PGUserEmailAddr], [Currency], [Gross], [PaymentStatus], [ReasonCode], [Tax]) VALUES (:jid, GETDATE(), :uqid, :currency, :value, :status, 'Coinbase Donate', :amount)");
            $stmt->execute([':jid' => $jid, ':uqid' => $uqid, ':currency' => $currency, ':value' => $value, ':status' => $status, ':amount' => $amount]);
            if ($stmt->rowCount()){
                header("Location: ".$url);

            }else{
                echo 'Database Error!';
            }
        }else{
            echo $response['error']['message'];
        }
    }
}
?>
