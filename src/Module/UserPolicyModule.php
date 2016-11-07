<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\Policy;
use PP\Portal\DbModel\UserPolicy;
use Illuminate\Database\Eloquent\Collection;

class UserPolicyModule extends AbstractContainer
{
    /**
     * @var UserPolicy
     */
    public $userPolicy;

    /**
     * @param int $id user_policy_id
     *
     * @return bool
     */
    public function getUerPolicy($id)
    {
        $item = $this->pool->getItem('UserPolicy/'.$id);
        $userPolicy = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            //$userPolicy = UserPolicy::with('policy.Advisor')->find($id);
            $userPolicy = UserPolicy::find($id);
            $this->pool->save($item->set($userPolicy));
        }

        if ($userPolicy) {
            $this->userPolicy = $userPolicy;

            return true;
        }

        return false;
    }

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
            $policy = $user->userPolicy()->get();
            $this->pool->save($item->set($policy));
        }

        return $this->serializing($policy);
    }

    /**
    * @param integer $policy_id
    */
    public function getPolicyDetail($policy_id)
    {
        $item = $this->pool->getItem('Policy/'.$policy_id);
        $policy = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $policy = Policy::with('advisor')->find($policy_id);
            $this->pool->save($item->set($policy));
        }

        return $policy;
    }

    private function serializing(Collection $policy)
    {
        // can move to db model like policy people
        /* @var $item \PP\Portal\DbModel\Policy */
        return $policy->map(function (Policy $item) {
            return [
                'policy_id'         => (int) $item->policy_id,
                'insurer'           => $item->insurer,
                'plan_name'         => $item->plan_name,
                'responsibility_id' => $item->responsibility_id,
                'user_policy_id'    => $item->pivot->id,
            ];
        });
    }
}
