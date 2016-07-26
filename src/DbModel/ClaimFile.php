<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $id
 * @property int $claim_id
 * @property string $filename
 * @property string $status
 */
class ClaimFile extends Model
{
    protected $table = 'member_portal_claim_file';

    public $timestamps = false;

    protected $hidden = ['claim_id'];
}
