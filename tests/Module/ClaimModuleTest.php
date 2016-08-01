<?php

namespace PP\Test;

class ClaimModuleTest extends \PHPUnit_Framework_TestCase
{
    protected $ClaimModule;
    protected $userPolicy;

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

        $c['UserPolicyModule'] = function ($c) {
            return new \PP\Portal\Module\UserPolicyModule($c);
        };
        $c['UserBankAccModule'] = function ($c) {
            return new \PP\Portal\Module\UserBankAccModule($c);
        };
        $c['UserPolicyModule']->getUerPolicy(1);

        $ClaimModule = new \PP\Portal\Module\ClaimModule($c);

        $this->ClaimModule = $ClaimModule;
        $this->userPolicy = $c['UserPolicyModule']->userPolicy;
    }

    public function testNewClaim()
    {
        $new = $this->ClaimModule->newClaim(1);
        $this->assertInstanceOf(\PP\Portal\DbModel\Claim::class, $new);
    }

    public function testSaveClaim()
    {
        $this->ClaimModule->newClaim(1);
        $data = [
            'currency' => 'USD',
            'amount' => '123',
        ];
        $this->ClaimModule->saveClaim($data);
        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testGetClaimList()
    {
        $claimList = $this->ClaimModule->getClaimList($this->userPolicy);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $claimList);

        $this->assertArrayHasKey('claim_id', $claimList[0]);
        $this->assertArrayHasKey('user_policy_id', $claimList[0]);
        $this->assertArrayHasKey('currency', $claimList[0]);
        $this->assertArrayHasKey('amount', $claimList[0]);

        $this->assertEquals(1, $claimList[0]['claim_id']);
        $this->assertNotNull($claimList[0]['created_at']);
        $this->assertNotNull($claimList[0]['updated_at']);
    }

    public function testGetInfoById()
    {
        $this->assertFalse($this->ClaimModule->geInfoById(1000));
        $this->assertTrue($this->ClaimModule->geInfoById(1));
        $this->assertInstanceOf(\PP\Portal\DbModel\Claim::class, $this->ClaimModule->claim);
        return $this->ClaimModule;
    }

    public function testNewBankAcc()
    {
        $data = [
            'iban'=>'sdfdsfdsf',
            'bank_swift_code'=>'dsfdsfdsf',
        ];
        $this->ClaimModule->newBankAcc($data);
        $this->assertInstanceOf(\PP\Portal\DbModel\ClaimBankAcc::class, $this->ClaimModule->bankInfo);
        return $this->ClaimModule;
    }

    public function testGetBankAcc()
    {
        $data = [
            'iban'=>'sdfdsfdsf',
        ];
        $this->assertTrue($this->ClaimModule->geInfoById(1));
        $this->ClaimModule->getBankAcc($data);
        $this->assertInstanceOf(\PP\Portal\DbModel\ClaimBankAcc::class, $this->ClaimModule->bankInfo);
        return $this->ClaimModule;
    }

    /**
     * @depends testNewBankAcc
     * @depends testGetBankAcc
     */
    public function testCalidateExtraClaimInfo($ClaimModule,$ClaimModule2)
    {
        $this->assertTrue($ClaimModule->validateExtraClaimInfo());
        $this->assertFalse($ClaimModule2->validateExtraClaimInfo());
        return $ClaimModule;
    }

    /**
     * @depends testNewBankAcc
     */
    public function testSaveBank($ClaimModule)
    {
        $data = [
            'iban'=>'sdfdsfdsf',
            'bank_swift_code'=>'dsfdsfdsf',
        ];
        $ClaimModule->saveBank($data);
        $this->expectOutputString('foo');
        echo 'foo';
    }

    /**
     * @depends testGetInfoById
     */
    public function testSaveBankToUserAccout($ClaimModule)
    {
        $data = [
            'iban'=>'sdfdsfdsf',
            'bank_swift_code'=>'dsfdsfdsf',
        ];
        $ClaimModule->saveBankToUserAccout($data);
        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testSaveExtraClaimInfoloop(){
        $this->ClaimModule->newClaim(1);
        $this->ClaimModule->claimExtraData['s'] = 'b';
        $this->ClaimModule->saveExtraClaimInfoloop();

        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testSaveExtraClaimInfoloop2(){
        $this->ClaimModule->newClaim(1);
        $data = [
            'iban'=>'sdfdsfdsf',
            'bank_swift_code'=>'dsfdsfdsf',
        ];
        $this->ClaimModule->newBankAcc($data);
        $this->ClaimModule->saveExtraClaimInfoloop();

        $this->expectOutputString('foo');
        echo 'foo';
    }
    
}
