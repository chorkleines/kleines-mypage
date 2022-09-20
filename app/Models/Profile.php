<?php

namespace App\Models;

use App\Enums\Part;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Profile extends Model
{
    use HasFactory, Sortable;

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
        'birthday' => 'date',
    ];

    protected $primaryKey = 'user_id';

    public $sortable = ['name_kana', 'grade', 'part'];

    private $partOrderRaw = 'CASE WHEN profiles.part LIKE "S" THEN 1 WHEN profiles.part LIKE "A" THEN 2 WHEN profiles.part LIKE "T" THEN 3 WHEN profiles.part LIKE "B" THEN 4 END';

    public function nameSortable($query, $direction)
    {
        return $query
            ->orderBy('name_kana', $direction)
            ->orderBy('grade', 'ASC')
            ->orderByRaw($this->partOrderRaw.' ASC')
            ->select('profiles.*');
    }

    public function gradeSortable($query, $direction)
    {
        return $query
            ->orderBy('grade', $direction)
            ->orderByRaw($this->partOrderRaw.' ASC')
            ->orderBy('name_kana', 'ASC')
            ->select('profiles.*');
    }

    public function partSortable($query, $direction)
    {
        return $query
            ->orderByRaw($this->partOrderRaw.' '.$direction)
            ->orderBy('grade', 'ASC')
            ->orderBy('name_kana', 'ASC')
            ->select('profiles.*');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }

    public function display_name()
    {
        $display_name =
            str_pad($this->grade, 2, 0, STR_PAD_LEFT).
            $this->part->value.
            ' '.
            $this->last_name.
            $this->first_name;

        return $display_name;
    }
}
