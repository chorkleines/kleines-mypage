<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'accounting_record_id',
        'price',
        'method',
    ];

    protected $casts = [
        'accounting_record_id' => 'int',
        'price' => 'int',
    ];

    protected $primaryKey = 'id';

    public function accouting_record()
    {
        return $this->belongsTo(AccountingRecord::class, 'accounting_record_id');
    }
}
