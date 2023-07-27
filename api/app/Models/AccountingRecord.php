<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'accounting_list_id',
        'user_id',
        'price',
        'paid_cash',
        'datetime',
        'is_paid',
    ];

    protected $casts = [
        'accounting_list_id' => 'int',
        'user_id' => 'int',
        'price' => 'int',
        'paid_cash' => 'int',
        'datetime' => 'datetime:Y-m-d H:i:s',
        'is_paid' => 'boolean',
    ];

    protected $visible = [
        'id',
        'price',
        'paid_cash',
        'datetime',
        'is_paid',
        'accounting_list',
        'accounting_payments',
    ];

    protected $primaryKey = 'id';

    public function accounting_list()
    {
        return $this->belongsTo(AccountingList::class, 'accounting_list_id');
    }

    public function accounting_payments()
    {
        return $this->hasMany(AccountingPayment::class, 'accounting_record_id');
    }
}
