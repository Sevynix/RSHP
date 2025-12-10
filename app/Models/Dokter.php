<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dokter extends Model
{
    use SoftDeletes;

    protected $table = 'dokter';
    protected $primaryKey = 'id_dokter';
    public $timestamps = false;
    
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'id_user',
        'alamat',
        'no_hp',
        'bidang_dokter',
        'jenis_kelamin',
        'deleted_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'iduser');
    }
}
