<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property string $address_type
 * @property string $nick_name
 * @property string $address_line_2
 * @property string $address_line_3
 * @property string $address_line_4
 * @property string $address_line_5
 * @property int $ppmid
 * @property string $status
 */
class Address extends Model
{
    protected $table = 'member_portal_address_user';
    protected $primaryKey = 'id';

    protected $fillable = ['nick_name', 'address_line_2', 'address_line_3', 'address_line_4', 'address_line_5'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'ppmid'  => 'integer',
        'old_id' => 'integer',
    ];

    /* move to holder info
    public function scopePolicyAddress(Builder $query)
    {
        return $query->where('status', 'active')
                ->where(function (Builder $query) {
                    $query->where('address_type', 'policy_address')
                          ->orWhere('address_type', 'mail_address');
                });
    }
    */

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUserAddress(Builder $query)
    {
        return $query->where('status', 'active');
                //->where('address_type', 'user');
    }
}
