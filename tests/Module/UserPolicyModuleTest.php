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

        $c['UserModule'] = function ($c) {
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

        $this->assertTrue($this->UserPolicyModule->getUerPolicy(7));
        $this->assertInstanceOf(\PP\Portal\DbModel\UserPolicy::class, $this->UserPolicyModule->userPolicy);

        $this->assertFalse($this->UserPolicyModule->getUerPolicy(1000));
    }

    public function testgetPolicyList()
    {
        $policyList = $this->UserPolicyModule->getPolicyList();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $policyList);

        $this->assertArrayHasKey('policy_id', $policyList[0]);
        $this->assertArrayHasKey('insurer', $policyList[0]);
        $this->assertArrayHasKey('plan_name', $policyList[0]);
        $this->assertArrayHasKey('cover', $policyList[0]);
        $this->assertArrayHasKey('options', $policyList[0]);
        $this->assertArrayHasKey('medical_currency', $policyList[0]);
        $this->assertArrayHasKey('payment_frequency', $policyList[0]);
        $this->assertArrayHasKey('payment_method', $policyList[0]);
        $this->assertArrayHasKey('start_date', $policyList[0]);
        $this->assertArrayHasKey('end_date', $policyList[0]);
        $this->assertArrayHasKey('responsibility_id', $policyList[0]);
        $this->assertArrayHasKey('status', $policyList[0]);
        $this->assertArrayHasKey('renew_date', $policyList[0]);
        $this->assertArrayHasKey('pivot', $policyList[0]);
        $this->assertArrayHasKey('advisor', $policyList[0]);
    }
}
