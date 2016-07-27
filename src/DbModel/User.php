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
 * @property string $forgot_str
 * @property string $forgot_expire
 */
class User extends Model
{
    protected $table = 'member_portal_user';
    protected $primaryKey = 'ppmid';

    public $timestamps = false;

    protected $guarded = ['ppmid', 'user_name', 'password'];

    protected $hidden = ['password', 'forgot_str', 'forgot_expire'];

    public function isRegister()
    {
        return $this->attributes['user_name'] === null &&
               $this->attributes['password'] === null;
    }

    public function passwordVerify($password)
    {
        return password_verify($password, $this->attributes['password']);
    }

    public function reNewInfo()
    {
        return $this->hasMany(__NAMESPACE__.'\UserInfoReNew', 'ppmid');
    }

    public function userAcc()
    {
        return $this->hasOne(__NAMESPACE__.'\UserBankAcc', 'ppmid');
    }

    public function userPolicy()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Policy', 'member_portal_user_policy', 'ppmid', 'policy_id')
                ->withPivot('id');
    }
}
