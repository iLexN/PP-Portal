<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $ppmid
 * @property string $currency
 * @property string $currency_receive
 */
class UserPreference extends Model
{
    protected $table = 'member_portal_user_preference';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['currency','currency_receive'];

    protected $casts = [
        'ppmid' => 'integer',
    ];
}
