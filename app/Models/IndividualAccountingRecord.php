<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualAccountingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'accounting_id',
        'list_id',
        'datetime',
        'price',
    ];

    protected $casts = [
        'user_id' => 'int',
        'accounting_id' => 'int',
        'list_id' => 'int',
        'datetime' => 'datetime',
        'price' => 'int',
    ];

    protected $primaryKey = null;

    public $incrementing = false;
}
