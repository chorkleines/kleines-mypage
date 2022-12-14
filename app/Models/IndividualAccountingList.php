<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualAccountingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'datetime',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    protected $primaryKey = 'list_id';

    public function individual_accounting_records()
    {
        return $this->hasMany(IndividualAccountingRecord::class, 'list_id');
    }
}
