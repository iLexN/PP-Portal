<?php

namespace PP\Portal\DbModel;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $policy_id
 * @property string $insurer
 * @property string $plan_name
 * @property string $responsibility_id
 * @property array $pivot
 */
class Policy extends Model
{
    protected $table = 'member_portal_policy';

    protected $primaryKey = 'policy_id';

    public $timestamps = false;

    protected $appends = ['renew_date','is_end'];

    protected $casts = [
        'responsibility_id' => 'integer',
    ];

    public function getRenewDateAttribute()
    {
        if ($this->attributes['end_date'] === null) {
            return;
        }

        $dateObj = Carbon::createFromFormat('Y-m-d', $this->attributes['end_date']);
        $dateObj->addDay();

        return $dateObj->toDateString();
    }

    public function getIsEndAttribute()
    {
        if ($this->attributes['end_date'] === null) {
            return true;
        }

        $endDateObj = Carbon::createFromFormat('Y-m-d', $this->attributes['end_date']);
        $todayDateObj = new Carbon();

        return $endDateObj->lte($todayDateObj);
    }

    public function address()
    {
        return $this->hasMany(__NAMESPACE__.'\Address', 'ref_id');
    }
}
