<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\Claim;
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
     *
     * @param int $id user_policy_id
     * @return \PP\Portal\DbModel\Claim
     */
    public function newClaim($id)
    {
        $this->claim = new Claim();
        $this->claim->user_policy_id = $id;
        return $this->claim;
    }

    public function saveClaim($data){
        foreach ($data as $k => $v) {
            $this->claim->{$k} = $v;
        }

        $this->claim->save();
    }

    /**
     *
     * @param UserPolicy $id user_policy_id
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
}
