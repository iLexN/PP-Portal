<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property string $address_type
 * @property string $nick_name
 * @property string $address_line_2
 * @property string $address_line_3
 * @property string $address_line_4
 * @property string $address_line_5
 * @property int $ref_id
 * @property string $status
 */
class Address extends Model
{
    protected $table = 'member_portal_address_user';
    protected $primaryKey = 'id';

    protected $fillable = ['nick_name','address_line_2','address_line_3','address_line_4','address_line_5'];

    protected $casts = [
        'ppmid' => 'integer',
        'ref_id' => 'integer',
        'old_id' => 'integer',
    ];

    public function scopePolicyAddress($query)
    {
        return $query->where('status', 'active')
                ->where(function ($query) {
                    $query->where('address_type','policy_address')
                          ->orWhere('address_type','mail_address');
                });
    }

    public function scopeUserAddress($query)
    {
        return $query->where('status', 'active')
                ->where('address_type','user');
    }

}
