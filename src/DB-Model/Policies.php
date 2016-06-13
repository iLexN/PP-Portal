<?php

namespace PP\Portal\dbModel;

use Illuminate\Database\Eloquent\Model as Model;

class Policies extends Model
{
    protected $table = 'policies';

    protected $primaryKey = 'Policy_ID';

    protected $visible = ['Client_NO', 'Sale_Date', 'New_Or_Renew', 'Policy_Type', 'Policy_Details', 'Insurance_Company', 'Distributing_Agent', 'Responsibility', 'Position', 'Status', 'Policy_Issue_Date', 'Issue_Responsibility', 'Policy_Number', 'Renewal_Status'];

    public $timestamps = false;

    public function client()
    {
        return $this->belongsTo(__NAMESPACE__.'\Client', 'Client_NO');
    }
}
