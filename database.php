<?php

/**
 * @author Kyle Thielk
 * Contains details for our database so we can login to mySQL
 **/

    $dbhost = "localhost";
    $dbuser = "ktrader_fender";
    $dbpassword = "tyler01s";
    $dbname = "ktrader_tracker";
    $domain = "";
    $myconnect = mysql_connect($dbhost, $dbuser, $dbpassword) or die('Error connecting to mysql');
    mysql_select_db($dbname);
?>
