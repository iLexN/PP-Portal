<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $id
 */
class UserPolicy extends Model
{
    protected $table = 'member_portal_user_policy';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function claims()
    {
        return $this->hasMany(__NAMESPACE__.'\Claim', 'user_policy_id');
    }
}
