<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $responsibility_id
 */
class HolderInfoUpdate extends Model
{
    protected $table = 'member_portal_policy_holder_update';

    //public $timestamps = false;

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'holder_id' => 'integer',
    ];
}
