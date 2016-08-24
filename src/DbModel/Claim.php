<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $claim_id
 * @property int $user_policy_id
 * @property string $status
 */
class Claim extends Model
{
    protected $table = 'member_portal_claim';
    protected $primaryKey = 'claim_id';

    //public $timestamps = false;

    protected $fillable = ['user_policy_id', 'currency', 'amount', 'date_of_treatment', 'diagnosis', 'payment_method', 'issue_to_whom', 'issue_address', 'currency_receive', 'status'];

    protected $casts = [
        'amount' => 'double',
        'user_policy_id' => 'integer',
    ];

    public function fileAttachments()
    {
        return $this->hasMany(__NAMESPACE__.'\ClaimFile', 'claim_id');
    }

    public function bankInfo()
    {
        return $this->hasOne(__NAMESPACE__.'\ClaimBankAcc', 'claim_id');
        // force no result return [] not null
        //return $this->hasMany(__NAMESPACE__.'\ClaimBankAcc', 'claim_id');
    }

    public function userPolicy()
    {
        return $this->belongsTo(__NAMESPACE__.'\UserPolicy', 'user_policy_id', 'id');
    }
}
