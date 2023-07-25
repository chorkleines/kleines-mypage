<?php

namespace App\Models;

use App\Models\IndividualAccountingList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualAccountingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'accounting_payment_id',
        'list_id',
        'datetime',
        'price',
    ];

    protected $casts = [
        'user_id' => 'int',
        'accounting_payment_id' => 'int',
        'list_id' => 'int',
        'datetime' => 'datetime:Y-m-d H:i:s',
        'price' => 'int',
    ];

    protected $visible = [
        'user_id',
        'accounting_payment_id',
        'list_id',
        'datetime',
        'price',
        'individual_accounting_list',
        'accounting_payment',
    ];

    protected $primaryKey = null;

    public $incrementing = false;

    public function individual_accounting_list()
    {
        return $this->belongsTo(IndividualAccountingList::class, 'list_id');
    }

    public function accounting_payment()
    {
        return $this->belongsTo(AccountingPayment::class, 'accounting_payment_id');
    }
}
