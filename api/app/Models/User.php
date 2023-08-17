<?php

namespace App\Models;

use App\Enums\Role;
use App\Enums\UserStatus;
use App\Models\AccountingRecord;
use App\Models\IndividualAccountingRecord;
use App\Models\Profile;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1,
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="PRESENT",
 *     ),
 *     @OA\Property(
 *         property="roles",
 *         type="array",
 *         @OA\Items(
 *             type="string",
 *             example="MASTER",
 *         ),
 *     ),
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'status',
        'roles',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => UserStatus::class,
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $visible = [
        'id',
        'email',
        'status',
        'roles',
        'profile',
        'accounting_records',
        'individual_accounting_records',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function hasRole($value)
    {
        return in_array($value, $this->roles);
    }

    public function hasAnyRole($values)
    {
        return count(array_intersect($values, $this->roles)) > 0;
    }

    public function accounting_records()
    {
        return $this->hasMany(AccountingRecord::class, 'user_id');
    }

    public function getArrears()
    {
        return $this->accounting_records->where('is_paid', false)->sum('price');
    }

    public function individual_accounting_records()
    {
        return $this->hasMany(IndividualAccountingRecord::class, 'user_id');
    }

    public function getBalance()
    {
        return $this->individual_accounting_records->sum('price');
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

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
