<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeletesWithUser;

class Perawat extends Model
{
    use SoftDeletesWithUser;

    protected $table = 'perawat';
    protected $primaryKey = 'id_perawat';
    public $timestamps = false;
    
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'id_user',
        'alamat',
        'no_hp',
        'jenis_kelamin',
        'pendidikan',
        'deleted_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'iduser');
    }
}