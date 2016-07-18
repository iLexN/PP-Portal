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
    public function getPolicyList(){
        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->c['UserModule']->user;

        $item = $this->c['pool']->getItem('User/'.$user->ppmid.'/policy/list');

        $policy = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            //$item->expiresAfter(3600/4);
            $policy = $user->userPolicy()->get();
            $this->c['pool']->save($item->set($policy));
        }

        /* @var $item \PP\Portal\DbModel\Policy */
        $out = $policy->map(function (Policy $item) {
            return [
                'policy_id' => $item->policy_id,
                'insurer' => $item->insurer,
                'plan_name' => $item->plan_name,
                'responsibility_id'=>$item->responsibility_id,
                'user_policy_id'=> $item->pivot->id,
            ];
        });
        return $out;
    }

    public function policyInfo($id){

        /* @var $policy \PP\Portal\DbModel\Policy */
        $item = $this->c['pool']->getItem('Policy/'.$id);
        $policy = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            //$item->expiresAfter(3600/4);
            $policy = Policy::find($id);
            $this->c['pool']->save($item->set($policy));
        }
        return $policy;
    }
}
