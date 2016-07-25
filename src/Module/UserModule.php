<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\User;
use PP\Portal\DbModel\UserInfoReNew;

/**
 * Description of UserModule.
 *
 * @author user
 */
class UserModule extends AbstractContainer
{
    /**
     * @var User
     */
    public $user;

    public function verifyUser($ar)
    {
        $user = User::where('ppmid', $ar['ppmid'])
                    ->where('date_of_birth', $ar['date_of_birth'])
                    ->first();


        if ($user) {
            return $user;
        }

        return false;
    }

    public function isUserNameExist($user_name)
    {
        $count = User::where('user_name', $user_name)->count();

        if ($count === 0) {
            return true;
        }

        return false;
    }

    /**
     * check user exist by id.
     *
     * @param int $id
     *
     * @return bool
     */
    public function isUserExistByID($id)
    {
        $item = $this->pool->getItem('User/'.$id.'/info');

        /* @var $user User */
        $user = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            //$item->expiresAfter(3600 * 12);
            $user = User::find($id);
            $this->pool->save($item->set($user));
        }

        if ($user) {
            $this->user = $user;

            return true;
        }

        return false;
    }

    public function isUserExistByUsername($username)
    {
        $user = User::where('user_name', $username)->first();
        if ($user) {
            $this->user = $user;

            return true;
        }

        return false;
    }

    public function savePassword($pass)
    {
        $this->user->password = $pass;
        $this->user->save();
        $this->clearUserCache();
    }

    private function clearUserCache()
    {
        $this->pool->deleteItem('User/'.$this->user->ppmid.'/info');
    }

    public function newInfoReNew()
    {
        $newInfo = new UserInfoReNew();
        $newInfo->ppmid = $this->user->ppmid;
        $newInfo->status = 'Pending';

        return $newInfo;
    }

    public function saveInfoReNew(UserInfoReNew $newInfo, $ar)
    {
        foreach ($ar as $k => $v) {
            $newInfo->{$k} = $v;
        }

        $newInfo->save();
    }

    public function saveSignUp($data)
    {
        $this->user->user_name = $data['user_name'];
        $this->user->password = $this->PasswordModule->passwordHash($data['password']);
        $this->user->save();
    }
}
