<?php

namespace App\Models;

use App\Enums\AccountingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'deadline',
        'admin',
    ];

    protected $casts = [
        'deadline' => 'date',
        'admin' => AccountingType::class,
    ];

    protected $primaryKey = 'accounting_id';

    public function accounting_records()
    {
        return $this->hasMany(AccountingRecord::class, 'accounting_id');
    }
}
