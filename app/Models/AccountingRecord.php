<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'accounting_id',
        'user_id',
        'price',
        'paid_cash',
        'datetime',
    ];

    protected $casts = [
        'accounting_id' => 'int',
        'user_id' => 'int',
        'price' => 'int',
        'paid_cash' => 'int',
        'datetime' => 'datetime',
    ];

    protected $primaryKey = 'id';

    public function accouting_list()
    {
        return $this->belongsTo(AccountingList::class, 'accounting_id');
    }
}
