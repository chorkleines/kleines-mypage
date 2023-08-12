<?php

namespace App\Models;

use App\Enums\AccountingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="AccountingList",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1,
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="2023年度団費集金",
 *     ),
 *     @OA\Property(
 *         property="deadline",
 *         type="string",
 *         example="2023-01-01 00:00:00",
 *     ),
 *     @OA\Property(
 *         property="admin",
 *         type="string",
 *         example="GENERAL",
 *     ),
 * )
 */
class AccountingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'deadline',
        'admin',
    ];

    protected $casts = [
        'deadline' => 'date:Y-m-d',
        'admin' => AccountingType::class,
    ];

    protected $visible = [
        'id',
        'name',
        'deadline',
        'admin',
    ];

    protected $primaryKey = 'id';

    public function accounting_records()
    {
        return $this->hasMany(AccountingRecord::class, 'accounting_list_id');
    }
}
