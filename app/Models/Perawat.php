<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perawat extends Model
{
    protected $table = 'perawat';
    protected $primaryKey = 'id_perawat';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'alamat',
        'no_hp',
        'jenis_kelamin',
        'pendidikan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'iduser');
    }
}