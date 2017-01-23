<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $responsibility_id
 */
class PolicyFile extends Model
{
    protected $table = 'member_portal_medical_policy_file_list';
    //protected $primaryKey = 'responsibility_id';

    public $timestamps = false;

    protected $casts = [
        'ppib' => 'integer',
    ];

    protected $appends = ['display_name'];

    public function getDisplayNameAttribute()
    {
        return pathinfo($this->attributes['file_name'], PATHINFO_FILENAME);
    }
}
