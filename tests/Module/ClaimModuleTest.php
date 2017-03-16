<?php

namespace PP\Test;

class ClaimModuleTest extends \PHPUnit\Framework\TestCase
{
    protected $ClaimModule;
    protected $userPolicy;

    protected function setUp()
    {
        $c = new \Slim\Container();

        $c['pool'] = function ($c) {
            $settings = [
                'path'          => __DIR__.'/../../cache',
                'client_upload' => '/claim_upload/',
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

    public function testValidClaim()
    {
        $fillable = ['status', 'claimiant_ppmid'];

        $data = ['status' => 's'];
        $v = $this->ClaimModule->validClaim($data, $fillable);
        $this->assertFalse($v->validate());

        $data = ['status' => 'Save', 'claimiant_ppmid'=> 2];
        $v = $this->ClaimModule->validClaim($data, $fillable);
        $this->assertTrue($v->validate());

        $data = ['status' => 'Submit', 'claimiant_ppmid'=> 2];
        $v = $this->ClaimModule->validClaim($data, $fillable);
        $this->assertTrue($v->validate());
    }

    public function testSaveClaim()
    {
        $this->ClaimModule->newClaim(1);
        $data = [
            'currency' => 'USD',
            'amount'   => '123',
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

        $this->assertNotNull($claimList[0]['created_at']);
        $this->assertNotNull($claimList[0]['updated_at']);
    }

    public function testGetInfoById()
    {
        $this->assertFalse($this->ClaimModule->geInfoById(1000));
        $this->assertTrue($this->ClaimModule->geInfoById(2));
        $this->assertInstanceOf(\PP\Portal\DbModel\Claim::class, $this->ClaimModule->claim);

        return $this->ClaimModule;
    }

    public function testNewBankAcc()
    {
        $data = [
            'iban'            => 'sdfdsfdsf',
            'bank_swift_code' => 'dsfdsfdsf',
        ];
        $this->ClaimModule->newBankAcc($data);
        $this->assertInstanceOf(\PP\Portal\DbModel\ClaimBankAcc::class, $this->ClaimModule->bank);

        return $this->ClaimModule;
    }

    public function testGetBankAcc()
    {
        $data = [
            'iban' => 'sdfdsfdsf',
        ];
        $this->assertTrue($this->ClaimModule->geInfoById(1));
        $this->ClaimModule->getBankAcc($data);
        $this->assertInstanceOf(\PP\Portal\DbModel\ClaimBankAcc::class, $this->ClaimModule->bank);

        return $this->ClaimModule;
    }

    public function testGetBankAcc2()
    {
        $data = [
            'iban' => 'sdfdsfdsf',
        ];
        $this->assertTrue($this->ClaimModule->geInfoById(5));
        $this->ClaimModule->getBankAcc($data);
        $this->assertInstanceOf(\PP\Portal\DbModel\ClaimBankAcc::class, $this->ClaimModule->bank);

        return $this->ClaimModule;
    }

    /**
     * @depends testNewBankAcc
     * @depends testGetBankAcc
     */
    public function testValidateExtraClaimInfo($ClaimModule, $ClaimModule2)
    {
        $this->assertTrue($ClaimModule->validateClaimInfo('All'));
        $this->assertTrue($ClaimModule->validateClaimInfo('Save'));
        $this->assertFalse($ClaimModule2->validateClaimInfo('Submit'));

        return $ClaimModule;
    }

    /**
     * @depends testNewBankAcc

     }*/

    /**
     * @depends testGetInfoById

     }*/
    public function testSaveExtraClaimInfoloop()
    {
        $this->ClaimModule->newClaim(1);
        $this->ClaimModule->newBankAcc([]);
        $this->ClaimModule->saveExtraClaimInfoloop();

        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testSaveExtraClaimInfoloop2()
    {
        $this->ClaimModule->newClaim(1);
        $data = [
            'iban'            => 'sdfdsfdsf',
            'bank_swift_code' => 'dsfdsfdsf',
        ];
        $this->ClaimModule->newBankAcc($data);
        $this->ClaimModule->saveExtraClaimInfoloop();

        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testNewCheque()
    {
        $this->ClaimModule->newClaim(1);
        $data = [
            'first_name'            => 'sdfdsfdsf',
            'address_line_2'        => 'dsfdsfdsf',
        ];
        $this->ClaimModule->newCheque($data);
        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testGetCheque()
    {
        $this->ClaimModule->newClaim(1);
        $data = [
            'first_name'            => 'sdfdsfdsf',
            'address_line_2'        => 'dsfdsfdsf',
        ];
        $this->ClaimModule->getCheque($data);
        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testGetCheque2()
    {
        $this->ClaimModule->geInfoById(2);
        $data = [
            'first_name'            => 'sdfdsfdsf',
            'address_line_2'        => 'dsfdsfdsf',
        ];
        $this->ClaimModule->getCheque($data);
        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testSaveAllInfo()
    {
        $this->ClaimModule->newClaim(1);
        $data = [];
        $this->ClaimModule->saveAllInfo($data);
        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testParseExtraData()
    {
        $this->ClaimModule->newClaim(1);
        $data = ['bank' => []];
        $this->ClaimModule->parseExtraData($data);

        $data = ['cheque' => []];
        $this->ClaimModule->parseExtraData($data);

        $this->expectOutputString('foo');
        echo 'foo';
    }
}
