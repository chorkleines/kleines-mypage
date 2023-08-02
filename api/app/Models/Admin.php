<?php

namespace App\Models;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'roles',
    ];

    protected $primaryKey = 'user_id';

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }

    private function is_role($value)
    {
        return in_array($value, Role::getValues());
    }

    /**
     * Get the roles attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function roles(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => array_filter(explode(',', $value), [$this, 'is_role']),
            set: fn ($value) => implode(',', $value),
        );
    }
}
