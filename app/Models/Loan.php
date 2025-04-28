<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    //
    protected $fillable = [
        'client_id', 'name', 'amount', 'rate', 'start_date', 'end_date'
    ];
}
