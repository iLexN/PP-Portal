<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $banker_transfer_id
 * @property int claim_id
 */
class ClaimBankAcc extends Model
{
    protected $table = 'member_portal_bank_transfer_claim';
    protected $primaryKey = 'banker_transfer_id';

    public $timestamps = false;

    protected $fillable = ['currency', 'account_user_name', 'account_number', 'iban', 'branch_code', 'bank_swift_code', 'bank_name', 'additional_information', 'intermediary_bank_swift_code'];

    protected $casts = [
        'claim_id' => 'integer',
    ];
}
