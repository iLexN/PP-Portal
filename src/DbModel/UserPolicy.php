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
class UserPolicy extends Model
{
    protected $table = 'member_portal_user_policy';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $casts = [
        'ppmid'        => 'integer',
        'policy_id'    => 'integer',
        'premium_paid' => 'double',
    ];

    public function claims()
    {
        return $this->hasMany(__NAMESPACE__.'\Claim', 'user_policy_id');
    }

    public function user()
    {
        return $this->belongsTo(__NAMESPACE__.'\User', 'ppmid', 'ppmid');
    }

    public function policyPlanFile()
    {
        return $this->belongsToMany(__NAMESPACE__.'\PlanFile', 'member_portal_user_plan_file', 'user_policy_id', 'plan_file_id');
    }

    /*
    public function userBankAcc()
    {
        return $this->hasManyThrough(
            __NAMESPACE__.'\UserBankAcc', __NAMESPACE__.'\User',
            'ppmid', 'ppmid', 'ppmid'
        );
    }*/

    public function policy()
    {
        return $this->hasOne(__NAMESPACE__.'\Policy', 'policy_id', 'policy_id');
    }
}
