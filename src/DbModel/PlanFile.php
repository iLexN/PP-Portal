<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $responsibility_id
 */
class PlanFile extends Model
{
    protected $table = 'member_portal_medical_plan_file_list';
    //protected $primaryKey = 'responsibility_id';

    public $timestamps = false;

    protected $casts = [
        'plan_id' => 'integer',
    ];

    protected $appends = ['display_name'];

    public function getDisplayNameAttribute()
    {
        return pathinfo($this->attributes['file_name'], PATHINFO_FILENAME);
    }
}
