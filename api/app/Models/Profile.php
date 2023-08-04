<?php

namespace App\Models;

use App\Enums\Part;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'name_kana',
        'grade',
        'part',
        'birthday',
    ];

    protected $casts = [
        'user_id' => 'int',
        'grade' => 'int',
        'part' => Part::class,
        'birthday' => 'date:Y-m-d',
    ];

    protected $primaryKey = 'user_id';

    protected $visible = [
        'last_name',
        'first_name',
        'name_kana',
        'grade',
        'part',
        'birthday',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
