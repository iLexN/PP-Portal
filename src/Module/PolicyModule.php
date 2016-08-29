<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\Policy;

/**
 * Description of UserModule.
 *
 * @author user
 */
class PolicyModule extends AbstractContainer
{
    /**
     * PolicyList with cache.
     *
     * @return \PP\Portal\DbModel\Policy
     */
    public function getPolicyList()
    {
        $user = $this->UserModule->user;
        $item = $this->pool->getItem('User/'.$user->ppmid.'/policyList');
        $policy = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            //$item->expiresAfter(3600/4);
            $policy = $user->userPolicy()->get();
            $this->pool->save($item->set($policy));
        }

        return $this->serializing($policy);
    }

    private function serializing($policy)
    {
        /* @var $item \PP\Portal\DbModel\Policy */
        return $policy->map(function (Policy $item) {
            return [
                'policy_id'         => $item->policy_id,
                'insurer'           => $item->insurer,
                'plan_name'         => $item->plan_name,
                'responsibility_id' => $item->responsibility_id,
                'user_policy_id'    => $item->pivot->id,
            ];
        });
    }

    /**
     * get PolicyInfo by id.
     *
     * @param int $id
     *
     * @return \PP\Portal\DbModel\Policy
     */
    public function policyInfo($id)
    {

        /* @var $policy \PP\Portal\DbModel\Policy */
        $item = $this->pool->getItem('Policy/'.$id);
        $policy = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            //$item->expiresAfter(3600/4);
            $policy = Policy::find($id);
            $this->pool->save($item->set($policy));
        }

        return $policy;
    }
}
