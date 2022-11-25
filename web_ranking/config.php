<?php
ini_set("display_errors", 0);

$db_host = '192.168.1.100';
$db_port = '1433';
$db_name = 'SILKROAD_R_SHARD';
$db_user = 'sa';
$db_pass = '123456';

$btnUrl = "https://silkroad.gamegami.com";

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
