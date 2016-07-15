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
class UserInfoReNew extends Model
{
    protected $table = 'member_portal_user_renew';
    protected $primaryKey = 'renew_id';

    public $timestamps = false;

    protected $fillable = ['title','middle_name','first_name','last_name','date_of_birth','nationality','phone_1','phone_2','address_line_2','address_line_3','address_line_4','address_line_5',];


}
