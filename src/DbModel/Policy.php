<?php

namespace PP\Portal\DbModel;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Model;

class Policy extends Model
{
    protected $table = 'member_portal_policy';
    protected $primaryKey = 'policy_id';

    //protected $hidden = ['pivot'];


    public $timestamps = false;

    protected $appends = ['renew_date'];

    public function getRenewDateAttribute(){
        
        if ( $this->attributes['end_date'] === null ) {
            return null;
        }
        
        $dateObj = Carbon::createFromFormat('Y-m-d', $this->attributes['end_date']);
        $dateObj->addDay();
        return $dateObj->toDateString();
    }


}
