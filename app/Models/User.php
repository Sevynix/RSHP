<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\SoftDeletesWithUser;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletesWithUser;

    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $timestamps = false;
    
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'deleted_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'iduser', 'idrole')
                    ->withPivot('status');
    }

    public function roleUsers()
    {
        return $this->hasMany(RoleUser::class, 'iduser', 'iduser');
    }

    public function pemilik()
    {
        return $this->hasOne(Pemilik::class, 'iduser', 'iduser');
    }

    public function dokter()
    {
        return $this->hasOne(Dokter::class, 'id_user', 'iduser');
    }

    public function perawat()
    {
        return $this->hasOne(Perawat::class, 'id_user', 'iduser');
    }
}
