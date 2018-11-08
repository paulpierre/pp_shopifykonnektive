<?php

ini_set('display_errors','1');

require_once realpath(dirname(__FILE__)."/konnektiveSDK.php");
$ksdk = new KonnektiveSDK('ASYNC');
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
