<?php

namespace PP\Portal\Module;

use Carbon\Carbon;
use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\ForgotUsername;
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

    /**
     * @param array $ar
     *
     * @return bool
     */
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

    /**
     * @param string $user_name
     *
     * @return bool
     */
    public function isUserNameExist($user_name)
    {
        $count = User::where('user_name', $user_name)->count();

        if ($count === 0) {
            return true;
        }

        return false;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isUserExistByEmail($email)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $this->user = $user;

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
        $item = $this->pool->getItem('User/'.$id);

        /* @var $user User */
        $user = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $user = User::find($id);
            $this->pool->save($item->set($user));
        }

        if ($user) {
            $this->user = $user;

            return true;
        }

        return false;
    }

    /**
     * @param string $username
     *
     * @return bool
     */
    public function isUserExistByUsername($username)
    {
        $user = User::where('user_name', $username)->first();
        if ($user) {
            $this->user = $user;

            return true;
        }

        return false;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isUserExistByForgotToken($token)
    {
        $user = User::where('forgot_str', $token)
                ->where('forgot_expire', '>', Carbon::now()->toDateTimeString())
                ->first();
        if ($user) {
            $this->user = $user;

            return true;
        }

        return false;
    }

    /**
     * @param string $pass
     */
    public function savePassword($pass)
    {
        //$this->user->password = $pass;
        $this->user->password = $this->PasswordModule->passwordHash($pass);
        $this->user->forgot_expire = null;
        $this->user->forgot_str = null;
        $this->user->save();
        $this->clearUserCache();
    }

    /**
     * @param string $str
     */
    public function saveForgot($str)
    {
        $this->user->forgot_str = $str;
        $this->user->forgot_expire = Carbon::now()->addHours(2)->toDateTimeString();
        $this->user->save();
        $this->clearUserCache();
    }

    /**
     * clear user info Cache.
     */
    private function clearUserCache()
    {
        $this->pool->deleteItem('User/'.$this->user->ppmid);
    }

    /**
     * @return UserInfoReNew
     */
    public function newInfoReNew()
    {
        $newInfo = new UserInfoReNew();
        $newInfo->ppmid = $this->user->ppmid;
        $newInfo->status = 'Pending';

        return $newInfo;
    }

    /**
     * @return UserInfoReNew
     */
    public function getInfoReNew()
    {
        $item = $this->pool->getItem('UserReNew/'.$this->user->ppmid);

        /* @var $user User */
        $renewInfo = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $renewInfo = $this->user->reNewInfo()->
                where('status', 'Pending')->
                orderBy('updated_at', 'desc')->
                first();
            $this->pool->save($item->set($renewInfo));
        }
        return $renewInfo;
    }

    /**
     * @param UserInfoReNew $newInfo
     * @param array         $ar
     */
    public function saveInfoReNew(UserInfoReNew $newInfo, $ar)
    {
        foreach ($ar as $k => $v) {
            $newInfo->{$k} = $v;
        }

        $newInfo->save();
    }

    /**
     * @param array $data
     */
    public function saveSignUp($data)
    {
        $this->user->user_name = $data['user_name'];
        $this->user->password = $this->PasswordModule->passwordHash($data['password']);
        $this->user->save();
        $this->clearUserCache();
    }

    /**
     * @return ForgotUsername
     */
    public function newForgotUsername()
    {
        return new \PP\Portal\DbModel\ForgotUsername();
    }

    /**
     * @param ForgotUsername $user
     * @param array          $data
     */
    public function saveForgotUsername(ForgotUsername $user, $data)
    {
        $user->name = $data['name'];
        $user->phone = $data['phone'];
        $user->email = $data['email'];
        $user->status = 'Pending';
        $user->ppmid = $this->user->ppmid;
        $user->save();
    }

    /**
     * @param string $phone
     *
     * @return bool
     */
    public function isPhoneMatch($phone)
    {
        return $this->user->phone_1 == $phone || $this->user->phone_2 == $phone;
    }
}
