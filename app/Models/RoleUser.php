<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeletesWithUser;

class RoleUser extends Model
{
    use SoftDeletesWithUser;

    protected $table = 'role_user';
    protected $primaryKey = 'idrole_user';
    public $timestamps = false;
    
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'iduser',
        'idrole',
        'status',
        'deleted_by',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }
}
