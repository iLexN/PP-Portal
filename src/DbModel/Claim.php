<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $claim_id
 * @property int $user_policy_id
 * @property string $payment_method
 * @property string $status
 */
class Claim extends Model
{
    protected $table = 'member_portal_claim';
    protected $primaryKey = 'claim_id';

    //public $timestamps = false;

    protected $fillable = ['user_policy_id', 'currency', 'amount', 'date_of_treatment', 'diagnosis', 'payment_method', 'claimiant_ppmid', 'currency_receive', 'status'];

    protected $casts = [
        'amount'          => 'double',
        'user_policy_id'  => 'integer',
        'claimiant_ppmid' => 'integer',
    ];

    public function fileAttachments()
    {
        return $this->hasMany(__NAMESPACE__.'\ClaimFile', 'claim_id');
    }

    public function bankInfo()
    {
        return $this->hasOne(__NAMESPACE__.'\ClaimBankAcc', 'claim_id');
    }

    public function cheque()
    {
        return $this->hasOne(__NAMESPACE__.'\ClaimCheque', 'claim_id');
    }

    /*
    public function userPolicy()
    {
        return $this->belongsTo(__NAMESPACE__.'\UserPolicy', 'user_policy_id', 'id');
    }**/

    public function getPaymentMethodAttribute($value)
    {
        return ucwords($value);
    }

    public function setPaymentMethodAttribute($value)
    {
        $this->attributes['payment_method'] = ucwords($value);
    }
}
