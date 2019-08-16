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
use LogicException;
use PHPUnit\Framework\TestCase;

class UAPITest extends TestCase
{

    private $_UAPI;
    private $_domain = 'example.com';
    private $_user = 'example';
    private $_password = 'example';

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

    public function testRequestGetJsonOrError()
    {
        if ($this->_domain != 'example.com') {
            $response = $this->_UAPI->Email->List_forwarders(array());
            $this->assertJson($response);
        }else{
            $this->markTestIncomplete( "Need to setup with a real domain, user and password to complete this test.\nCan't make the Exception test, Guzzle throw and error and block all.\nWhen i try -- assertException -- it don't work. \n***** Don't know why, please help :) ****" );
        }

    }

}
