<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\Claim;
use PP\Portal\DbModel\UserPolicy;
use PP\Portal\DbModel\ClaimBankAcc;

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
     *
     * @var \PP\Portal\DbModel\ClaimBankAcc
     */
    public $bankInfo;

    /**
     *
     * @param int $id user_policy_id
     * @return \PP\Portal\DbModel\Claim
     */
    public function newClaim($id)
    {
        $this->claim = new Claim();
        $this->claim->user_policy_id = $id;
        $this->claim->status = 'Save';
        return $this->claim;
    }

    public function saveClaim($data){
        foreach ($data as $k => $v) {
            $this->claim->{$k} = $v;
        }

        $this->claim->save();
        $this->clearCache();
    }

    /**
     *
     * @param UserPolicy $userPolicy
     * @return type
     */
    public function getClaimList(UserPolicy $userPolicy)
    {
        $item = $this->pool->getItem('UserPolicy/'.$userPolicy->id.'/claim/list');

        $claim = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            //$item->expiresAfter(3600/4);
            $claim = $userPolicy->claims()->get();
            $this->pool->save($item->set($claim));
            
        }
        
        return $claim;
    }

    public function geInfoById($id){
        $item = $this->pool->getItem('Claim/'.$id);
        $claim = $item->get();
        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            //$item->expiresAfter(3600/4);
            $claim = Claim::with('fileAttachments','bankInfo')->find($id);
            $this->pool->save($item->set($claim));
        }
        if ( $claim ){
            $this->claim = $claim;

            return true;
        }
        return false;
    }

    public function newBankAcc() {
        $this->bankInfo = new ClaimBankAcc();
        return $this->bankInfo;
    }

    public function saveBank($data){
        foreach ($data as $k => $v) {
            $this->bankInfo->{$k} = $v;
        }

        $this->bankInfo->save();
    }

    public function clearCache()
    {
        $this->pool->deleteItem('UserPolicy/'.$this->claim->user_policy_id.'/claim/list');
        $this->pool->deleteItem('Claim/'.$this->claim->claim_id);
    }
}
