<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $responsibility_id
 */
class InsurerPlan extends Model
{
    protected $table = 'member_portal_insurer_plan_management';

    public $timestamps = false;

    protected $casts = [
        'insurer_id' => 'integer',
    ];
}
