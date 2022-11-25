<?php
ini_set("display_errors", 0);

$db_host = '192.168.1.100';
$db_port = '1433';
$db_name = 'SILKROAD_R_ACCOUNT';
$db_user = 'sa';
$db_pass = '123456';

$serverName = 'Silkroad Online';

$register_method = 1; // 1 = website , 2 = _Rigid_Register_User
$silk_own = 0; // silk for new acc
$silk_own_premium = 0; // premium silk for new acc

$reCAPTCHA_enable = false;
$reCAPTCHA_site_key = '';
$reCAPTCHA_secret_key = '';

/*
you have to setup webhook url first.
https://commerce.coinbase.com/settings/notifications
https://yourdomain.com/api/coinbase/postback.php
*/
$coinbase_api_support_email = '';
$coinbase_api_key = '';
$coinbase_secret = '';

$downloads = array(
    array(
        'image' => '/assets/img/mega.png',
        'name' => 'Mega.nz',
        'url' => 'https://mega.nz/folder/5BgyzJoY#chnrFL1NvErJky8FIJtWxA/file/lB5nnDqD'
    ),
    array(
        'image' => '/assets/img/mediafire.png',
        'name' => 'Mediafire',
        'url' => 'https://www.mediafire.com/file/rhtt6wvmcouad7e/RIGID_v234.7z/file'
    ),
    array(
        'image' => '/assets/img/google_drive.png',
        'name' => 'Google Drive',
        'url' => '#'
    ),
    array(
        'image' => '/assets/img/rsbot.png',
        'name' => 'RSBot',
        'url' => 'https://github.com/SDClowen/RSBot/releases'
    ),
    array(
        'image' => '/assets/img/rsbot.png',
        'name' => 'RSBot',
        'url' => 'https://github.com/SDClowen/RSBot/releases'
    )
);

$uniques = array(
    array(
        'id' => 1954,
        'code' => 'MOB_CH_TIGERWOMAN',
        'name' => 'Tiger Girl',
        'points' => 1
    ),
    array(
        'id' => 1982,
        'code' => 'MOB_OA_URUCHI',
        'name' => 'Uruchi',
        'points' => 2
    ),
    array(
        'id' => 2002,
        'code' => 'MOB_KK_ISYUTARU',
        'name' => 'Isyutaru',
        'points' => 3
    ),
    array(
        'id' => 3810,
        'code' => 'MOB_TK_BONELORD',
        'name' => 'Lord Yarkan',
        'points' => 4
    ),
    array(
        'id' => 3875,
        'code' => 'MOB_RM_TAHOMET',
        'name' => 'Demon Shaitan',
        'points' => 5
    ),
    array(
        'id' => 14778,
        'code' => 'MOB_AM_IVY',
        'name' => 'Captain Ivy',
        'points' => 2
    ),
    array(
        'id' => 5871,
        'code' => 'MOB_EU_KERBEROS',
        'name' => 'Cerberus',
        'points' => 1
    ),
    array(
        'id' => 3877,
        'code' => 'MOB_RM_ROC',
        'name' => 'Roc',
        'points' => 15
    ),
    array(
        'id' => 14839,
        'code' => 'MOB_TQ_WHITESNAKE',
        'name' => 'Medusa',
        'points' => 10
    ),
);

$package = array(
    'silk' => array(
        array(
            'name' => 'Silk',
            'currency' => 'USD',
            'amount' => 1,
            'value' => 100,
            'description' => 'Pay 1 USD for 100 Silk',
        ),
        array(
            'name' => 'Silk',
            'currency' => 'USD',
            'amount' => 5,
            'value' => 500,
            'description' => 'Pay 5 USD for 500 Silk',
        ),
        array(
            'name' => 'Silk',
            'currency' => 'USD',
            'amount' => 10,
            'value' => 1000,
            'description' => 'Pay 10 USD for 1000 Silk',
        ),
        array(
            'name' => 'Silk',
            'currency' => 'USD',
            'amount' => 25,
            'value' => 2500,
            'description' => 'Pay 25 USD for 2500 Silk',
        ),
        array(
            'name' => 'Silk',
            'currency' => 'USD',
            'amount' => 50,
            'value' => 5000,
            'description' => 'Pay 50 USD for 5000 Silk',
        ),
        array(
            'name' => 'Silk',
            'currency' => 'USD',
            'amount' => 75,
            'value' => 7500,
            'description' => 'Pay 75 USD for 7500 Silk',
        ),
        array(
            'name' => 'Silk',
            'currency' => 'USD',
            'amount' => 100,
            'value' => 10000,
            'description' => 'Pay 100 USD for 10000 Silk',
        ),
        array(
            'name' => 'Silk',
            'currency' => 'USD',
            'amount' => 250,
            'value' => 25000,
            'description' => 'Pay 250 USD for 25000 Silk',
        )
    ),
    'silk-premium' => array(
        array(
            'name' => 'Silk Premium',
            'currency' => 'USD',
            'amount' => 5,
            'value' => 500,
            'description' => 'Pay 5 USD for 500 Silk Premium',
        )
    )
);

if($db_host || $db_port || $db_name || $db_user || $db_pass){

    try
    {
        $conn = new PDO( "sqlsrv:server=$db_host,$db_port; TrustServerCertificate=true; Database=$db_name", "$db_user", "$db_pass",
            array(
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION
            )
        );
    }
    catch(Exception $e)
    {
        die( print_r( $e->getMessage() ) );
    }
}else{
    die('Sql information is not correct!');
}
?>
