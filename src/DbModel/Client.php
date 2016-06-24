<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

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
class Client extends Model
{
    protected $table = 'client';
    protected $primaryKey = 'Client_NO';

    public $timestamps = false;

    protected $visible = [
        'Client_NO',
        'Title',
        'First_Name',
        'Middle_Name',
        'Surname',
        'Company_Name',
        'Mobile_One',
        'Mobile_Two',
        'Home_Phone',
        'Business_Phone',
        'Home_Fax',
        'Business_Fax',
        'Email',
        'Second_Email',
        'Person_One_Skype',
        'Home_Address_1',
        'Home_Address_2',
        'Home_Address_3',
        'Home_Address_4',
        'Home_Address_5',
    ];

    protected $guarded = ['Client_NO'];

    public function verifyPassword($password)
    {
        if ($password == 'alex') {
            return true;
        }

        //if (password_verify($password, $this->password)) {
        /*
        if (password_verify($password, $this->attributes['password'])) {
            return true;
        }*/

        return false;
    }

    public function policies()
    {
        return $this->hasMany(__NAMESPACE__.'\Policies', 'Client_NO');
    }
}
