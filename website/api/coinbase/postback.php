<?php

require_once('../../config.php');
$payload = file_get_contents('php://input');
$secret = $coinbase_secret;
$headerName = 'x-cc-webhook-signature';
$headers = getallheaders();
$signraturHeader = isset($headers[$headerName]) ? $headers[$headerName] : null;

//SHA256 HMAC of payload with secret
$signature = hash_hmac('sha256', $payload, $secret);

//check if null
if($signraturHeader == null){
    die('Not Allowed');
}

if (hash_equals($signature, $signraturHeader)) {

    $jsonString = json_decode($payload, true);

    $id = $jsonString['event']['data']['id'];
    $event_type = $jsonString['event']['type'];
    $support_email = $jsonString['event']['data']['support_email'];
    $status = $jsonString['event']['data']['timeline'][0]['status'];

    //error_log( print_r($jsonString, TRUE) );

    if($event_type == 'charge:confirmed'){
        if ($support_email == $coinbase_api_support_email){
            $stmt = $conn->prepare("SELECT * FROM [GB_JoymaxPortal].[dbo].[APH_PaypalIPN] WHERE [PGUserEmailAddr] = :uqid AND [PaymentStatus] = 'NEW'");
            $stmt->execute([':uqid' => $id]);

            if ($stmt->rowCount()){
                foreach ($stmt->fetchAll() as $value){
                    $amount = $value['Tax'];
                    $jid = $value['JID'];
                    $value = $value['Gross'];
                }

                $stmt = $conn->prepare("INSERT INTO [GB_JoymaxPortal].[dbo].[APH_ChangedSilk] VALUES (NULL, NULL, NULL, :portalJid, :silk_own, 0, 1, 2, GETDATE(), DATEADD(YEAR, 10, GETDATE()), 'Y')");
                $stmt->execute([':portalJid' => $jid, ':silk_own' => $value]);

                $stmt = $conn->prepare("UPDATE [GB_JoymaxPortal].[dbo].[APH_PaypalIPN] SET [PaymentStatus] = :status WHERE [PGUserEmailAddr] = :uqid");
                $stmt->execute([':status' => $status, ':uqid' => $id]);

            }else{
                die('Package not valid');
            }
        }else{
            die('Email not Correct');
        }
    }else{
        die('Status Not Completed');
    }
}
?>
