<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $status
 * @property int $ppmid
 */
class ForgotUsername extends Model
{
    protected $table = 'member_portal_forgot_username';

    //public $timestamps = false;

    protected $fillable = ['name', 'phone', 'email', 'status'];

    protected $casts = [
        'ppmid' => 'integer',
    ];
}
