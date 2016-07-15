<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $ppmid
 * @property string $title
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $date_of_birth
 * @property string $user_name
 * @property string $password
 * @property string $nationality
 * @property string $email
 * @property string $phone_1
 * @property string $phone_2
 * @property string $address_line_2
 * @property string $address_line_3
 * @property string $address_line_4
 * @property string $address_line_5
 */
class User extends Model
{
    protected $table = 'member_portal_user';
    protected $primaryKey = 'ppmid';

    public $timestamps = false;

    protected $guarded = ['ppmid','user_name','password'];

    protected $hidden = ['password','user_name'];


    public function isRegister(){

        return $this->attributes['user_name'] === null &&
               $this->attributes['password'] === null;
    }

    public function passwordVerify($password){
        return password_verify ( $password ,  $this->attributes['password'] );
    }

    public function reNewInfo()
    {
        return $this->hasMany(__NAMESPACE__.'\UserInfoReNew', 'ppmid');
    }
    
}
