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
