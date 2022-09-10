<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinBoardHashtag extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulletin_board_id',
        'hashtag',
    ];

    protected $casts = [
        'bulletin_board_id' => 'int',
    ];

    protected $primaryKey = ['bulletin_board_id', 'hashtag'];

    public $incrementing = false;
}
