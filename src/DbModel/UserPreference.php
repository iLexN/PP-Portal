<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $banker_transfer_id
 * @property int $ppmid
 */
class UserPreference extends Model
{
    protected $table = 'member_portal_user_preference';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['currency','currency_receive'];

    protected $hidden = ['ppmid'];

    protected $casts = [
        'ppmid' => 'integer',
    ];
}
