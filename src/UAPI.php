<?php
/**
 * Created by Bruno Pouliot.
 * Email: dev.lecanardnoir@gmail.com
 * Date: 8/14/19
 * Time: 11:17 PM
 */

namespace cpanel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use LogicException;

class UAPI
{

    private static $client = null;
    private $_module = "";
    private $_function = "";
    private $_args = array(null);

    /**
     * @return string
     */
    public function getModule(): string
    {
        return $this->_module;
    }

    /**
     * @return Client|null
     */
    public static function getClient(): ?Client
    {
        return self::$client;
    }

    public static function setClient( Client $newClient ): void
    {
        self::$client = $newClient;
    }


    /**
     * UAPI constructor.
     * @param string $domain
     * @param string $user
     * @param string $password
     */
    public function __construct( string $domain, string $user, string $password )
    {

        self::$client = new Client(
            array(
                "base_uri" => "https://cpanel." . $domain . "/execute/",
                "auth" => [ $user, $password ]
            )
        );
    }

    /**
     * @param $key
     * @return $this
     */
    public function __get($key)
    {
        if ( ! property_exists($this, $key) && ! method_exists( $this, $key )){
            $this->_module = $key;
        }else{
            throw new LogicException("Uapi->$key exist. Only unknown key is accepted.");
        }
        return $this;
    }

    /**
     * @param $key
     * @param array|null $args
     * @return string
     */
    public function __call($key, array $args = null ): string
    {
        if ( ! property_exists($this, $key) && ! method_exists( $this, $key )){
            $this->_function = $key;
            $this->_args = $args;
            return $this->request();
        }else{
            throw new LogicException("Uapi->$key exist. Only unknown method is accepted.");
        }
    }

    /**
     * @return string
     */
    private function request(): string
    {
        $uri =  $this->getModule() . "/" . $this->_function;
        $response= null;

        try {
            $response = self::$client->request(
                "POST",
                $uri,
                ["query" => $this->_args[0]]
            );
            return $response->getBody()->getContents();

        } catch ( GuzzleException $e) {

            $errFile = fopen( "./cPanelUAPI-error.log", "a" );
            fwrite($errFile, $e->getMessage() . "\n");
            fclose($errFile);
            return "Error line: ". $e->getMessage();

        }
    }

}







