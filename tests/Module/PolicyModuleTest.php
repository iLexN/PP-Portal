<?php

namespace PP\Test;

class PolicyModuleTest extends \PHPUnit_Framework_TestCase
{
    protected $PolicyModule;

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

        $c['UserModule'] = function ($c) {
            return new \PP\Portal\Module\UserModule($c);
        };
        $c['UserModule']->isUserExistByID(2);


        $PolicyModule = new \PP\Portal\Module\PolicyModule($c);

        $this->PolicyModule = $PolicyModule;
    }

    public function testGetPolicyList()
    {
        $policyList = $this->PolicyModule->getPolicyList();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $policyList);

        $this->assertArrayHasKey('policy_id', $policyList[0]);
        $this->assertArrayHasKey('insurer', $policyList[0]);
        $this->assertArrayHasKey('plan_name', $policyList[0]);
        $this->assertArrayHasKey('responsibility_id', $policyList[0]);
        $this->assertArrayHasKey('user_policy_id', $policyList[0]);

        $this->assertEquals(1, $policyList[0]['policy_id']);
    }

    public function testPolicyInfo()
    {
        $policy = $this->PolicyModule->policyInfo(2);

        $this->assertInstanceOf(\PP\Portal\DbModel\Policy::class, $policy);

        $this->assertEquals(2, $policy->policy_id);
        $this->assertEquals('2020-02-17', $policy->end_date);
        $this->assertEquals('2020-02-18', $policy->renew_date);
    }
}
