<?php
ini_set("display_errors", 0);

$db_host = '192.168.1.100';
$db_port = '1433';
$db_name = 'SILKROAD_R_ACCOUNT';
$db_user = 'sa';
$db_pass = '123456';

$saltKey = 'eset5ag.nsy-g6ky5.mp';

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
