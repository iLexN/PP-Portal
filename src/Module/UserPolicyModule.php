<?php

namespace PP\Portal\Module;

use Illuminate\Database\Eloquent\Collection;
use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\Policy;
use PP\Portal\DbModel\User;
use PP\Portal\DbModel\UserPolicy;

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
     * @return array
     */
    public function getPolicyList()
    {
        $user = $this->UserModule->user;
        $item = $this->pool->getItem('User/'.$user->ppmid.'/policyList');
        $policy = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $policy = $this->getUserPolicy($user);
            $this->pool->save($item->set($policy));
        }

        //return $this->serializing($policy);
        return $policy;
    }

    /**
     * @param User $user
     *
     * @return array
     */
    private function getUserPolicy(User $user)
    {
        $list = $this->getPolicyListDetails($user);
        $files = UserPolicy::whereIn('id', $this->getUserPolicyID($list))->with('policyPlanFile')->get();

        $keyed = $files->keyBy('id');

        return $this->formatList($list, $keyed->toArray());
    }

    private function formatList(Collection $list, array $files)
    {
        return $list->map(function (Policy $item) use ($files) {
            $ar = $item->toArray();
            $ar['planfile'] = $files[$item->pivot->id] ? $files[$item->pivot->id]['policy_plan_file'] : [];

            $people = $item->policyuser->map(function (User $item) {
                $user = $item->userName();
                $user['premium_paid'] = $item->pivot->premium_paid;
                $user['relationship'] = $item->pivot->relationship;

                return $user;
            });
            $ar['policyuser'] = $people;

            return $ar;
        });
    }

    /**
     * @param User $user
     *
     * @return Collection
     */
    private function getPolicyListDetails(User $user)
    {
        return $user->userPolicy()->withPivot('premium_paid')
                    //->with('advisor')->with('plan')->with('planfile')->with('policyfile')
                    ->with('advisor', 'plan', 'planfile', 'policyfile', 'policyuser')
                    //->PolicyPlanFile()
                    ->get();
    }

    /**
     * @param int $policy_id
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

    private function getUserPolicyID(Collection $policy)
    {
        // can move to db model like policy people
        /* @var $item \PP\Portal\DbModel\Policy */
        return $policy->map(function (Policy $item) {
            return $item->pivot->id;
        });
    }
}
