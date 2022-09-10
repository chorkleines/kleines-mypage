<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinBoardContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulletin_board_id',
        'user_id',
        'datetime',
        'content',
    ];

    protected $casts = [
        'bulletin_board_id' => 'int',
        'user_id' => 'int',
        'datetime' => 'datetime',
    ];

    protected $primaryKey = ['bulletin_board_id', 'datetime'];

    public $incrementing = false;
}
