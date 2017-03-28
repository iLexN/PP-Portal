<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $id
 * @property int $ppmid
 * @property int $policy_id
 * @property float $premium_paid
 * @property string $relationship relationship
 * @property \PP\Portal\DbModel\Policy $policy
 * @property \PP\Portal\DbModel\User $user
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
