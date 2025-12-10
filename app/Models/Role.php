<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

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
