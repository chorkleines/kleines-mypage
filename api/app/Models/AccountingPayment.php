<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="AccountingPayment",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1,
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="integer",
 *         example=0,
 *     ),
 *     @OA\Property(
 *         property="method",
 *         type="string",
 *         example="INDIVIDUAL_ACCOUNTING",
 *     ),
 * )
 */
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

    protected $visible = [
        'id',
        'price',
        'method',
        'accounting_record',
    ];

    protected $primaryKey = 'id';

    public function accounting_record()
    {
        return $this->belongsTo(AccountingRecord::class, 'accounting_record_id');
    }
}
