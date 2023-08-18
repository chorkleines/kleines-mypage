<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="IndividualAccountingList",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1,
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="2022年度引き継ぎ",
 *     ),
 *     @OA\Property(
 *         property="datetime",
 *         type="string",
 *         example="2023-01-01 00:00:00",
 *     ),
 * )
 */
class IndividualAccountingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'datetime',
    ];

    protected $casts = [
        'datetime' => 'datetime:Y-m-d H:i:s',
    ];

    protected $visible = [
        'id',
        'name',
        'datetime',
        'individual_accounting_records',
    ];

    protected $primaryKey = 'id';

    public function individual_accounting_records()
    {
        return $this->hasMany(IndividualAccountingRecord::class, 'individual_accounting_list_id');
    }
}
