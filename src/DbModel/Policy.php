<?php

namespace PP\Portal\DbModel;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $policy_id
 * @property string $insurer
 * @property string $plan_name
 * @property string $responsibility_id
 * @property string $status
 * @property array $pivot
 * @property array|\Illuminate\Database\Eloquent\Collection $policyuser
 */
class Policy extends Model
{
    protected $table = 'member_portal_policy';

    protected $primaryKey = 'policy_id';

    public $timestamps = false;

    protected $appends = ['renew_date', 'is_end', 'region_email'];

    protected $casts = [
        'responsibility_id' => 'integer',
    ];

    public function getRenewDateAttribute()
    {
        $dateObj = Carbon::createFromFormat('Y-m-d', $this->attributes['end_date']);
        $dateObj->addDay();

        return $dateObj->toDateString();
    }

    public function getRegionEmailAttribute()
    {
        switch ($this->attributes['region']) {
            case 'CN':
                $email = 'claims.cn@pacificprime.com';
                break;
            case 'ROW':
                $email = 'claims@pacificprime.com';
                break;
            case 'UAE':
                $email = 'sgclientservices@pacificprime.com';
                break;
            case 'SG':
                $email = 'sgclientservices@pacificprime.com';
                break;
            case 'HK':
                $email = 'claims.hk@pacificprime.com';
                break;

            default:
                $email = 'claims@pacificprime.com';
                break;
        }

        return $email;
    }

    public function getIsEndAttribute()
    {
        $endDateObj = Carbon::createFromFormat('Y-m-d', $this->attributes['end_date']);
        $todayDateObj = new Carbon();

        return $endDateObj->lte($todayDateObj);
    }

    /* move to holder info
    public function address()
    {
        return $this->hasMany(__NAMESPACE__.'\Address', 'ref_id');
    }
    */

    public function advisor()
    {
        return $this->hasOne(__NAMESPACE__.'\Advisor', 'responsibility_id', 'responsibility_id');
    }

    public function plan()
    {
        return $this->hasOne(__NAMESPACE__.'\InsurerPlan', 'id', 'plan_id');
    }

    public function planfile()
    {
        return $this->hasMany(__NAMESPACE__.'\PlanFile', 'plan_id', 'plan_id');
    }

    public function policyfile()
    {
        return $this->hasMany(__NAMESPACE__.'\PolicyFile', 'ppib', 'policy_id');
    }

    public function policyuser()
    {
        return $this->belongsToMany(__NAMESPACE__.'\User', 'member_portal_user_policy', 'policy_id', 'ppmid')
                ->withPivot('premium_paid', 'relationship');
    }
}
