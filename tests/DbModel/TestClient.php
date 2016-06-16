<?php

namespace PP\Test;

class TestClient extends \PHPUnit_Framework_TestCase
{
    private $c;
    
    public function testFind()
    {
        $client = \PP\Portal\DbModel\Client::find(1);
        $this->assertEquals(1,$client->Client_NO);
        $this->assertTrue($client->verifyPassword('alex'));
        $this->assertFalse($client->verifyPassword('123'));
    }

    public function testNotFind()
    {
        $client = \PP\Portal\DbModel\Client::find(10000);
        $this->assertNull($client);
    }

}
