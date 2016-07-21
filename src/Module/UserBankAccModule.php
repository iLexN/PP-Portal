<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\UserBankAcc;

/**
 * Description of UserModule.
 *
 * @author user
 */
class UserBankAccModule extends AbstractContainer
{
    public function newBlankAcc($id){
        $newInfo = new UserBankAcc();
        $newInfo->ppmid = $id;
        return $newInfo;
    }

    public function saveData(UserBankAcc $acc,$data){
        foreach ($data as $k => $v) {
            $acc->{$k} = $v;
        }

        $acc->save();
        $this->clearCache();
    }

    public function getByUserID(){
        $id = $this->UserModule->user->ppmid;

        $item = $this->pool->getItem('User/'.$id.'/bankacc');

        $info = $item->get();
        
        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            //$item->expiresAfter(3600/4);
            $info = $this->UserModule->user->userAcc()->first();
            $this->pool->save($item->set($info));
        }

        return $info;
    }

    private function clearCache()
    {
        $this->pool->deleteItem('User/'.$this->UserModule->user->ppmid.'/bankacc');
    }
}
