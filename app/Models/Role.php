<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeletesWithUser;

class Role extends Model
{
    use SoftDeletesWithUser;

    protected $table = 'role';
    protected $primaryKey = 'idrole';
    public $timestamps = false;
    
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'nama_role',
        'deleted_by',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'idrole', 'iduser')
                    ->withPivot('status');
    }
}
