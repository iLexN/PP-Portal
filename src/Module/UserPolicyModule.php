<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\UserPolicy;

/**
 * Description of UserModule.
 *
 * @author user
 */
class UserPolicyModule extends AbstractContainer
{
    /**
     *
     * @param int $id user_policy_id
     * @return type
     */
    public function getClaimList($id)
    {
        
        $item = $this->pool->getItem('UserPolicy/'.$id);

        $userPolicy = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            //$item->expiresAfter(3600/4);
            $userPolicy = UserPolicy::find($id);
            $this->pool->save($item->set($userPolicy));
        }
        
        return $userPolicy;
    }
}
