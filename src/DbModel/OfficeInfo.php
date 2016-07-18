<?php

namespace PP\Portal\DbModel;

use Illuminate\Database\Eloquent\Model as Model;

class OfficeInfo extends Model
{
    protected $table = 'member_portal_contact_details';
    protected $primaryKey = 'contact_details_id';

    public $timestamps = false;

}
