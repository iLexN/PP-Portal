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
    /**
     * @param int $id
     *
     * @return UserBankAcc
     */
    public function newBlankAcc($id)
    {
        $newInfo = new UserBankAcc();
        $newInfo->ppmid = $id;

        return $newInfo;
    }

    /**
     * @param array $data
     * @param array $fillable
     *
     * @return \Valitron\Validator
     */
    public function validBank($data, $fillable)
    {
        $v = new \Valitron\Validator($data, $fillable);
        $v->rule('required', ['iban', 'bank_swift_code']);

        return $v;
    }

    /**
     * @param UserBankAcc $acc
     * @param array       $data
     */
    public function saveData(UserBankAcc $acc, $data)
    {
        foreach ($data as $k => $v) {
            $acc->{$k} = $v;
        }

        $acc->save();
        //$this->clearCache($acc->ppmid);
    }

    /**
     * @param UserBankAcc $acc
     */
    public function delBank(UserBankAcc $acc)
    {
        $acc->delete();
        //$this->clearCache($acc->ppmid);
    }

    public function getByUserID()
    {
        //$id = $this->UserModule->user->ppmid;

        //$item = $this->pool->getItem('User/'.$id.'/bankacc');

        //$info = $item->get();

        //if ($item->isMiss()) {
        //    $item->lock();
        //    $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            //$item->expiresAfter(3600/4);
            $info = $this->UserModule->user->userAcc()->get();
        //    $this->pool->save($item->set($info));
        //}

        return $info;
    }

    //private function clearCache($id)
    //{
    //    $this->pool->deleteItem('User/'.$id.'/bankacc');
    //}
}
