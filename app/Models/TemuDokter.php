<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemuDokter extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'temu_dokter';
    protected $primaryKey = 'idreservasi_dokter';
    public $timestamps = false;
    
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'tanggal',
        'waktu_daftar',
        'no_antrian',
        'idpet',
        'status',
        'idrole_user',
        'deleted_by',
    ];

    // Relationships
    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }

    public function dokter()
    {
        return $this->belongsTo(RoleUser::class, 'idrole_user', 'idrole_user');
    }

    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class, 'idreservasi_dokter', 'idreservasi_dokter');
    }
}
