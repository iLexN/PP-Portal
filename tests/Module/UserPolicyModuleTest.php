<?php

namespace PP\Test;

class UserPolicyModuleTest extends \PHPUnit_Framework_TestCase
{
    protected $UserPolicyModule;

    protected function setUp()
    {
        $c = new \Slim\Container();

        $c['pool'] = function ($c) {
            $settings = [
                'path' => __DIR__.'/../cache/data',
            ];
            $driver = new \Stash\Driver\FileSystem($settings);

            return new \Stash\Pool($driver);
        };

        $c['dataCacheConfig'] = ['expiresAfter' => 1];

        $c['UserModule'] = function($c){
            return new \PP\Portal\Module\UserModule($c);
        };
        $c['UserModule']->isUserExistByID(9677);


        $UserPolicyModule = new \PP\Portal\Module\UserPolicyModule($c);

        $this->UserPolicyModule = $UserPolicyModule;
    }

    public function testGetPolicy()
    {
        $this->assertTrue($this->UserPolicyModule->getUerPolicy(1));
        $this->assertInstanceOf(\PP\Portal\DbModel\UserPolicy::class, $this->UserPolicyModule->userPolicy);

        //$this->assertTrue($this->UserPolicyModule->getUerPolicy(7));
        //$this->assertInstanceOf(\PP\Portal\DbModel\UserPolicy::class, $this->UserPolicyModule->userPolicy);

        $this->assertFalse($this->UserPolicyModule->getUerPolicy(1000));
    }

    public function testgetPolicyList(){
        $policyList = $this->UserPolicyModule->getPolicyList();
 
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $policyList);
 
        $this->assertArrayHasKey('policy_id', $policyList[0]);
        $this->assertArrayHasKey('insurer', $policyList[0]);
        $this->assertArrayHasKey('plan_name', $policyList[0]);
        $this->assertArrayHasKey('responsibility_id', $policyList[0]);
        $this->assertArrayHasKey('user_policy_id', $policyList[0]);
 
        $this->assertEquals(1, $policyList[0]['policy_id']);
    }

}
