<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\Claim;
use PP\Portal\DbModel\ClaimBankAcc;
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
    public $bankInfo;

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
        //$this->claim->status = 'Save';

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
            //$item->expiresAfter(3600/4);
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
            //$item->expiresAfter(3600/4);
            $claim = Claim::with('fileAttachments', 'bankInfo')->find($id);
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
        $this->bankInfo = new ClaimBankAcc();
        $this->validateBankInfo($data);
    }

    /**
     * @param array $data
     */
    public function getBankAcc($data)
    {
        $this->bankInfo = $this->claim->bankInfo()->first();
        $this->validateBankInfo($data);
    }

    /**
     * @param array $data
     */
    private function validateBankInfo($data)
    {
        $vb = new \Valitron\Validator($data, $this->bankInfo->getFillable());
        $vb->rule('required', ['iban', 'bank_swift_code']);
        $this->claimExtraData['bank'] = $vb;
    }

    /**
     * @param array $data
     */
    public function saveBank($data)
    {
        foreach ($data as $k => $v) {
            $this->bankInfo->{$k} = $v;
        }

        $this->bankInfo->save();
    }

    /**
     * @param array $data
     */
    public function saveBankToUserAccout($data)
    {

        /* @var $userPolicy \PP\Portal\DbModel\UserPolicy */
        $userPolicy = $this->claim->userPolicy()->first();

        /* @var $userBankAcc \PP\Portal\DbModel\UserBankAcc */
        $userBankAcc = $userPolicy->userBankAcc()->first();

        if (!$userBankAcc) {
            $userBankAcc = $this->UserBankAccModule->newBlankAcc($userPolicy->ppmid);
            $this->UserBankAccModule->saveData($userBankAcc, $data);
        }
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    public function validateExtraClaimInfo($status)
    {
        if ($status === 'Save') {
            return true;
        }

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
        switch ($k) {
            case 'bank':
                $data = $v->data();
                //$this->saveBankToUserAccout($data);
                $data['claim_id'] = $this->claim->claim_id;
                $this->saveBank($data);

                break;

            default:
                break;
        }
    }

    public function clearCache()
    {
        $this->pool->deleteItem('UserPolicy/'.$this->claim->user_policy_id.'/claimList');
        $this->pool->deleteItem('Claim/'.$this->claim->claim_id);
    }
}
