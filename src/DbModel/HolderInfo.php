<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $responsibility_id
 */
class HolderInfo extends Model
{
    protected $table = 'member_portal_policy_holder';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function reNewInfo()
    {
        return $this->hasMany(__NAMESPACE__.'\HolderInfoUpdate', 'holder_id');
    }
}
