<?php

namespace PP\Test;

use ReflectionClass;
use ReflectionProperty;

class TestAbstractContainer extends \PHPUnit_Framework_TestCase
{
    private $c;

    public function testFind()
    {

        $this->assertClassHasAttribute('c', \PP\Portal\AbstractClass\AbstractContainer::class);
       
    }

    public function setUpContainer()
    {
        $app = new \Slim\App();
        $c = $app->getContainer();
        
        return $c;
    }
    
}
