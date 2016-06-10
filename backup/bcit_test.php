

<?php

/*

// Server in the this format: <computer>\<instance name> or 
// <server>,<port> when using a non default port number
$server = '10.10.1.174\SIOSQL01';

// Connect to MSSQL
$link = mssql_connect($server, 'cccom\sgoulet', 'cccsgoulet');

if (!$link) {
    die("Couldn't connect to SQL Server. Error: " . mssql_get_last_message());
}
*/



ini_set('display_errors', '1');

    // $myServer = "winsrv22.somedns.co.uk:22320";//this style works as well
    $servername = "10.10.1.174:65335";
    $myUser = "cccom\sgoulet";
    $myPass = 'cccsgoulet';

    //connection to the database
    $dbhandle = mssql_connect($servername, $myUser, $myPass)
        or die("Couldn't connect to SQL Server"); 
    if($dbhandle) {
     echo "Success, Connected\r\n";
    } else {
        echo "problem :( \r\n";
    }


?>