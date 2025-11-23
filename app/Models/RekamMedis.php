<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';
    protected $primaryKey = 'idrekam_medis';
    public $timestamps = true;

    protected $fillable = [
        'idreservasi_dokter',
        'anamnesa',
        'temuan_klinis',
        'diagnosa',
        'dokter_pemeriksa',
        'created_at',
        'updated_at'
    ];

    // Relationships
    public function temuDokter()
    {
        return $this->belongsTo(TemuDokter::class, 'idreservasi_dokter', 'idreservasi_dokter');
    }

    public function pet()
    {
        return $this->hasOneThrough(
            Pet::class,
            TemuDokter::class,
            'idreservasi_dokter', // Foreign key on temu_dokter table
            'idpet', // Foreign key on pet table
            'idreservasi_dokter', // Local key on rekam_medis table
            'idpet' // Local key on temu_dokter table
        );
    }

    public function dokterPemeriksa()
    {
        return $this->belongsTo(User::class, 'dokter_pemeriksa', 'iduser');
    }

    // Alias for dokterPemeriksa for consistency
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
