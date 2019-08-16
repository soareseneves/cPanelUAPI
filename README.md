# cPanelUAPI
![version](https://img.shields.io/badge/version-1.0-brightgreen)<br><br>
[![Build Status](https://travis-ci.com/LeCanardNoir/cPanelUAPI.svg?branch=master)](https://travis-ci.com/LeCanardNoir/cPanelUAPI) ![Dependencies](https://img.shields.io/badge/GuzzleHttp%2FCLient-Dependencies-blue) ![PHP](https://img.shields.io/badge/PHP-%5E7.2-brightgreen)

PHP Class inspired by [scorpionslh/cpanel-uapi-php-class](https://github.com/scorpionslh/cpanel-uapi-php-class).
***
### All the cPanel API reference
https://documentation.cpanel.net/display/DD/Guide+to+UAPI
***
#### Usage
```php
use cPanel;

$cpanel = new UAPI( $domain, $user, $password );

$query = array(
    "param1" => "value",
    "param2" => "value"
);

$response = $cpanel->Module->function( $query );

echo $response; //json

```
#### Example
```php
use cPanel;

$cpanel = new UAPI( $domain, $user, $password );

$query = array(
    "domain"        => $domain,
    "regex"         => "coordo",
    "api.column"    => 1,
    "api.columns_0" => "dest",
    "api.columns_1" => "forward"
);

$response = $cpanel->Email->list_forwarders( $query );

$response = (object) json_decode( $response, true ); //decode json

var_dump( $response->data );

```
***
This is my very first share php class and PHPunit test, so if any have an advice on this library please be my guest. :grin: