<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\Claim;
use PP\Portal\DbModel\ClaimBankAcc;
use PP\Portal\DbModel\ClaimCheque;
use PP\Portal\DbModel\UserPolicy;

/**
 * Description of UserModule.
 *
 * @author user
 */
class ClaimModule extends AbstractContainer
{
    /**
     * @var \PP\Portal\DbModel\Claim
     */
    public $claim;

    /**
     * @var \PP\Portal\DbModel\ClaimBankAcc
     */
    public $bank;

    /**
     * @var \PP\Portal\DbModel\ClaimCheque
     */
    public $cheque;

    public $claimExtraData = [];

    /**
     * @param int $id user_policy_id
     *
     * @return \PP\Portal\DbModel\Claim
     */
    public function newClaim($id)
    {
        $this->claim = new Claim();
        $this->claim->user_policy_id = $id;

        return $this->claim;
    }

    /**
     * @param array $data
     * @param array $fillable
     *
     * @return \Valitron\Validator
     */
    public function validClaim($data, $fillable)
    {
        $v = new \Valitron\Validator($data, $fillable);
        $v->rule('required', ['status']);
        $v->rule('dateFormat', ['date_of_treatment'], 'Y-m-d');
        $v->rule('in', ['status'], ['Save', 'Submit']);

        return $v;
    }

    /**
     * @param array $data
     */
    public function saveClaim($data)
    {
        foreach ($data as $k => $v) {
            $this->claim->{$k} = $v;
        }

        $this->claim->save();
        $this->clearCache();
    }

    /**
     * @param UserPolicy $userPolicy
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getClaimList(UserPolicy $userPolicy)
    {
        $item = $this->pool->getItem('UserPolicy/'.$userPolicy->id.'/claimList');

        $claim = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $claim = $userPolicy->claims()->orderBy('created_at', 'desc')->get();
            $this->pool->save($item->set($claim));
        }

        return $claim;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function geInfoById($id)
    {
        $item = $this->pool->getItem('Claim/'.$id);
        $claim = $item->get();
        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $claim = Claim::find($id);
            $this->pool->save($item->set($claim));
        }
        if ($claim) {
            $this->claim = $claim;

            return true;
        }

        return false;
    }

    /**
     * @param array $data
     */
    public function newBankAcc($data)
    {
        $this->bank = new ClaimBankAcc();
        $this->validateBankInfo($data);
    }

    /**
     * @param array $data
     */
    public function getBankAcc($data)
    {
        $bank = $this->claim->bankInfo()->first();
        if (!$bank) {
            $this->newBankAcc($data);
        } else {
            $this->bank = $bank;
            $this->validateBankInfo($data);
        }
    }

    /**
     * @param array $data
     */
    private function validateBankInfo($data)
    {
        $vb = new \Valitron\Validator($data, $this->bank->getFillable());
        $vb->rule('required', ['iban', 'bank_swift_code']);
        $this->claimExtraData['bank'] = $vb;
    }

    /**
     * @return bool
     */
    public function validateClaimInfo()
    {
        foreach ($this->claimExtraData as $v) {
            if (!$v->validate()) {
                return false;
            }
        }

        return true;
    }

    public function saveExtraClaimInfoloop()
    {
        foreach ($this->claimExtraData as $k => $v) {
            $this->saveExtraClaimData($k, $v);
        }
    }

    private function saveExtraClaimData($k, $v)
    {
        $data = $v->data();
        //$this->saveBankToUserAccout($data);
        $data['claim_id'] = $this->claim->claim_id;
        //$this->saveBank($data);

        foreach ($data as $dk => $dv) {
            $this->{$k}->{$dk} = $dv;
        }
        $this->{$k}->save();
    }

    public function clearCache()
    {
        $this->pool->deleteItem('UserPolicy/'.$this->claim->user_policy_id.'/claimList');
        $this->pool->deleteItem('Claim/'.$this->claim->claim_id);
    }

    /**
     * @param array $data
     */
    public function newCheque($data)
    {
        $this->cheque = new ClaimCheque();
        $this->validateCheque($data);
    }

    /**
     * @param array $data
     */
    public function getCheque($data)
    {
        $cheque = $this->claim->cheque()->first();
        if (!$cheque) {
            $this->newCheque($data);
        } else {
            $this->cheque = $cheque;
            $this->validateCheque($data);
        }
    }

    /**
     * @param array $data
     */
    private function validateCheque($data)
    {
        $vb = new \Valitron\Validator($data, $this->cheque->getFillable());
        $vb->rule('required', ['first_name', 'address_line_2']);
        $this->claimExtraData['cheque'] = $vb;
    }

    public function saveAllInfo($data)
    {
        $this->saveClaim($data);
        $this->saveExtraClaimInfoloop();
    }

    public function parseExtraData($data)
    {
        if ($this->haveBankData($data)) {
            $this->getBankAcc($data['bank']);
        } elseif ($this->haveChequeData($data)) {
            $this->getCheque($data['cheque']);
        }
    }

    private function haveBankData($data)
    {
        return isset($data['bank']) && is_array($data['bank']);
    }

    private function haveChequeData($data)
    {
        return isset($data['cheque']) && is_array($data['cheque']);
    }
}
