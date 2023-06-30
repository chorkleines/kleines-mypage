<?php

namespace App\Models;

use App\Enums\BulletinBoardStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinBoard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'status',
    ];

    protected $casts = [
        'user_id' => 'int',
        'status' => BulletinBoardStatus::class,
    ];

    protected $primaryKey = 'bulletin_board_id';

    public function content()
    {
        return $this->hasOne(BulletinBoardContent::class, 'bulletin_board_id')->orderBy('datetime', 'desc');
    }

    public function histories()
    {
        return $this->hasMany(BulletinBoardContent::class, 'bulletin_board_id');
    }

    public function hashtags()
    {
        return $this->hasMany(BulletinBoardHashtag::class, 'bulletin_board_id');
    }
}
