<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $banker_transfer_id
 * @property int claim_id
 */
class ClaimCheque extends Model
{
    protected $table = 'member_portal_cheque';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['name', 'address_line_2', 'address_line_3', 'address_line_4', 'address_line_5'];

    protected $casts = [
        'claim_id' => 'integer',
    ];
}
