<?php

use cpanel\UAPI;

require "vendor/autoload.php";

$domain     = 'example.com';
$user       = 'example';
$password   = 'example';

$module     = 'Email';
$function   = 'list_forwarders';
$query      = array(
    'domain' => $domain,
    "api.column" => 1,
    "api.columns_0" => 'dest',
    "api.columns_1" => 'forward'
);



$uapi = new UAPI( $domain, $user, $password );

$response = $uapi->$module->$function($query);

print_r($response);

