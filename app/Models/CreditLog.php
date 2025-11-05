<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditLog extends Model
{
    public $fillable = [
        'user_id',
        'amount',
        'previous_balance',
        'new_balance',
        'reason'
    ];
}
