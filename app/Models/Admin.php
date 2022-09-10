<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
    ];

    protected $casts = [
        'role' => Role::class,
    ];

    protected $primaryKey = 'user_id';

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
