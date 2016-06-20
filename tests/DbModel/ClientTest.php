<?php

namespace PP\Test;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    private $c;

    public function testFind()
    {
        $client = \PP\Portal\DbModel\Client::find(1);

        if ($client) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }

        $this->assertEquals(1, $client->Client_NO);
        $this->assertTrue($client->verifyPassword('alex'));
        $this->assertFalse($client->verifyPassword('123'));
    }

    public function testNotFind()
    {
        $client = \PP\Portal\DbModel\Client::find(10000);
        $this->assertNull($client);
        if ($client) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
}
