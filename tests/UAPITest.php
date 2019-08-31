<?php
/**
 * Created by Bruno Pouliot.
 * Email: dev.lecanardnoir@gmail.com
 * Date: 8/15/19
 * Time: 9:12 PM
 */

namespace tests;

use cpanel\UAPI;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use LogicException;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class UAPITest extends TestCase
{

    private $_UAPI;
    private $_domain    = 'example.com';
    private $_user      = 'example';
    private $_password  = 'example';

    protected function setUp(): void
    {
        $this->_UAPI = new UAPI($this->_domain, $this->_user, $this->_password);
    }

    public function test__getReturnThisInstance()
    {
        $this->assertInstanceOf(UAPI::class, $this->_UAPI->test);
    }

    public function test__getSetModule()
    {
        $this->_UAPI->testMe;
        $this->assertSame( 'testMe', $this->_UAPI->getModule() );
    }

    public function test_getPropretiesDontExiste()
    {
        $this->expectException(LogicException::class);
        $this->_UAPI->_module;
    }

    public function test__call()
    {

        $mock = $this->createPartialMock(UAPI::class, array(
            '__call'
        ));

        $mock->expects($this->any())
            ->method('__call')
            ->willReturnCallback(function($name, $args) {
                return $name;
            } );

        $this->assertSame('foo', $mock->foo());
        return $mock;
    }

    public function testGetClient()
    {
        $this->assertInstanceOf( Client::class, $this->_UAPI->getClient() );
    }

    public function testGetModuleReturnName()
    {
        $this->_UAPI->testMe;
        $this->assertSame( 'testMe', $this->_UAPI->getModule() );
    }

    public function testRequestGetJsonAndNoError()
    {

        $body = '{
            "status": 1,
            "messages": null,
            "warnings": null,
            "errors": null,
            "data": []
          }';
        
        
        $mock = new MockHandler([new Response(200, [], $body)]);
        $handler = HandlerStack::create($mock);
        $this->_UAPI::setClient( new Client(['handler' => $handler]) );
        
        $response = $this->_UAPI->Email->list_forwarders( array() );

        $this->assertJson( $response );
        //no error
        $json = json_decode($response, true);
        $this->assertEquals( 1, $json['status'] );

    }

    public function testRequestGetJsonWithError()
    {
        $body = '{
            "messages": null,
            "status": 0,
            "data": null,
            "errors": [
              "The system could not find the function “test” in the module “Email”."
            ],
            "metadata": {},
            "warnings": null
          }';

        $mock = new MockHandler([new Response(200, [], $body)]);
        $handler = HandlerStack::create($mock);
        $this->_UAPI::setClient( new Client(['handler' => $handler]) );

        $response = $this->_UAPI->Email->test( array() );
        
        $json = json_decode( $response, true );
        $this->assertEquals(0 ,$json['status']);
        
    }

    public function testExceptionErrorInfo()
    {

        $response = $this->_UAPI->Email->list_forwarders(array());
        $this->assertStringStartsWith("Error line:", $response);        
    
    }

    /** 
     * TODO: test cPanel Error -> function not find in Module
     * TODO: test cPanel Error -> Module don't exist
    */

}
