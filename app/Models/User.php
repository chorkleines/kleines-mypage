<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use App\Enums\UserStatus;
use App\Models\AccountingRecord;
use App\Models\IndividualAccountingRecord;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'status',
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
    protected $primaryKey = 'user_id';

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    public function is_admin($value)
    {
        return $this->admin->role->value === $value;
    }

    public function accountingRecords()
    {
        return $this->hasMany(AccountingRecord::class, 'user_id');
    }

    public function getArrears()
    {
        return $this->accountingRecords->where('datetime', null)->sum('price');
    }

    public function individualAccountingRecords()
    {
        return $this->hasMany(IndividualAccountingRecord::class, 'user_id');
    }

    public function getBalance()
    {
        return $this->individualAccountingRecords->sum('price');
    }
}
