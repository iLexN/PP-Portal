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
    }
}
