<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $id
 * @property int $user_policy_id
 * @property int $plan_file_id
 */
class UserPolicyPlanFile extends Model
{
    protected $table = 'member_portal_user_plan_file';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $casts = [
        'user_policy_id' => 'integer',
        'plan_file_id'   => 'integer',
    ];
}
