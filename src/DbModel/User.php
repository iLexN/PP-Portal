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
 * @property string $forgot_str
 * @property string $forgot_expire
 * @property int $holder_id
 * @property string $profile_permission
 */
class User extends Model
{
    protected $table = 'member_portal_user';
    protected $primaryKey = 'ppmid';

    public $timestamps = false;

    protected $guarded = ['ppmid', 'user_name', 'password', 'holder_id'];

    protected $hidden = ['password', 'forgot_str', 'forgot_expire'];

    protected $casts = [
        'holder_id' => 'integer',
    ];

    public function isRegister()
    {
        return $this->attributes['user_name'] === null &&
               $this->attributes['password'] === null;
    }

    public function userName()
    {
        return [
            'ppmid'       => (int) $this->attributes['ppmid'],
            'title'       => $this->attributes['title'],
            'first_name'  => $this->attributes['first_name'],
            'middle_name' => $this->attributes['middle_name'],
            'last_name'   => $this->attributes['last_name'],
        ];
    }

    public function passwordVerify($password)
    {
        return password_verify($password, $this->attributes['password']);
    }

    public function needReHash()
    {
        if (password_needs_rehash($this->attributes['password'], PASSWORD_DEFAULT)) {
            return true;
        }
        
        return false;
    }

    public function reNewInfo()
    {
        return $this->hasMany(__NAMESPACE__.'\UserInfoReNew', 'ppmid');
    }

    public function userAcc()
    {
        //return $this->hasOne(__NAMESPACE__.'\UserBankAcc', 'ppmid');
        return $this->hasMany(__NAMESPACE__.'\UserBankAcc', 'ppmid');
    }

    public function userPreference()
    {
        return $this->hasOne(__NAMESPACE__.'\UserPreference', 'ppmid');
    }

    public function userPolicy()
    {
        return $this->belongsToMany(__NAMESPACE__.'\Policy', 'member_portal_user_policy', 'ppmid', 'policy_id')
                ->withPivot('id');
    }

    public function address()
    {
        return $this->hasMany(__NAMESPACE__.'\Address', 'ppmid');
    }
}
