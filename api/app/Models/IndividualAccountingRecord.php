<?php

namespace App\Models;

use App\Models\IndividualAccountingList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="IndividualAccountingRecord",
 *     @OA\Property(
 *         property="datetime",
 *         type="string",
 *         example="2023-01-01 00:00:00",
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="integer",
 *         example=0,
 *     ),
 * )
 */
class IndividualAccountingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'accounting_payment_id',
        'individual_accounting_list_id',
        'datetime',
        'price',
    ];

    protected $casts = [
        'user_id' => 'int',
        'accounting_payment_id' => 'int',
        'individual_accounting_list_id' => 'int',
        'datetime' => 'datetime:Y-m-d H:i:s',
        'price' => 'int',
    ];

    protected $visible = [
        'datetime',
        'price',
        'individual_accounting_list',
        'accounting_payment',
        'user',
    ];

    protected $primaryKey = null;

    public $incrementing = false;

    public function individual_accounting_list()
    {
        return $this->belongsTo(IndividualAccountingList::class, 'individual_accounting_list_id');
    }

    public function accounting_payment()
    {
        return $this->belongsTo(AccountingPayment::class, 'accounting_payment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
