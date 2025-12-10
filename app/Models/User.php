<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $timestamps = false;
    
    // Soft delete columns
    const DELETED_AT = 'deleted_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'deleted_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all roles through the pivot table (many-to-many)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'iduser', 'idrole')
                    ->withPivot('status');
    }

    /**
     * Get all role_user pivot records for this user
     */
    public function roleUsers()
    {
        return $this->hasMany(RoleUser::class, 'iduser', 'iduser');
    }

    /**
     * Get the pemilik record if this user is a pet owner
     */
    public function pemilik()
    {
        return $this->hasOne(Pemilik::class, 'iduser', 'iduser');
    }

    /**
     * Get the dokter record if this user is a doctor
     */
    public function dokter()
    {
        return $this->hasOne(Dokter::class, 'id_user', 'iduser');
    }

    /**
     * Get the perawat record if this user is a nurse
     */
    public function perawat()
    {
        return $this->hasOne(Perawat::class, 'id_user', 'iduser');
    }
}
