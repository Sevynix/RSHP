<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeletesWithUser;

class RekamMedis extends Model
{
    use HasFactory, SoftDeletesWithUser;

    protected $table = 'rekam_medis';
    protected $primaryKey = 'idrekam_medis';
    public $timestamps = false;
    
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'anamnesa',
        'temuan_klinis',
        'diagnosa',
        'dokter_pemeriksa',
        'idreservasi_dokter',
        'deleted_by',
    ];

    public function temuDokter()
    {
        return $this->belongsTo(TemuDokter::class, 'idreservasi_dokter', 'idreservasi_dokter');
    }

    public function dokterPemeriksa()
    {
        return $this->belongsTo(RoleUser::class, 'dokter_pemeriksa', 'idrole_user');
    }

    public function dokter()
    {
        return $this->dokterPemeriksa();
    }

    public function details()
    {
        return $this->hasMany(DetailRekamMedis::class, 'idrekam_medis', 'idrekam_medis');
    }

    public function detailRekamMedis()
    {
        return $this->hasMany(DetailRekamMedis::class, 'idrekam_medis', 'idrekam_medis');
    }
}
