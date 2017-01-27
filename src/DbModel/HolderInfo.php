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

    protected $hidden = ['client_no', 'service_office', 'title', 'first_name', 'middle_name', 'last_name'];

    protected $fillable = ['policy_address_line_2', 'policy_address_line_3', 'policy_address_line_4', 'policy_address_line_5', 'holder_id', 'status'];

    public function reNewInfo()
    {
        return $this->hasMany(__NAMESPACE__.'\HolderInfoUpdate', 'holder_id');
    }

    public function renew()
    {
        return $this->hasOne(__NAMESPACE__.'\HolderInfoUpdate', 'holder_id')->where('status', 'Pending')->orderBy('created_at', 'desc');
    }
}
