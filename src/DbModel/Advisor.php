<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * @property int $responsibility_id
 */
class Advisor extends Model
{
    protected $table = 'member_portal_responsibility';
    protected $primaryKey = 'responsibility_id';

    public $timestamps = false;

    protected $casts = [
        'contact_details_id' => 'integer',
    ];
}
