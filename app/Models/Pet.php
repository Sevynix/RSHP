<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pet extends Model
{
    protected $table = 'pet';
    protected $primaryKey = 'idpet';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'warna_tanda',
        'idpemilik',
        'idras_hewan'
    ];

    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'idpemilik', 'idpemilik');
    }

    public function rasHewan()
    {
        return $this->belongsTo(RasHewan::class, 'idras_hewan', 'idras_hewan');
    }

    public function jenisHewan()
    {
        return $this->hasOneThrough(
            JenisHewan::class,
            RasHewan::class,
            'idras_hewan',
            'idjenis_hewan',
            'idras_hewan',
            'idjenis_hewan'
        );
    }

    public function temuDokter()
    {
        if (!class_exists('App\Models\TemuDokter')) {
            return collect([]);
        }
        return $this->hasMany(\App\Models\TemuDokter::class, 'idpet', 'idpet');
    }

    public function rekamMedis()
    {
        return $this->hasManyThrough(
            RekamMedis::class,
            TemuDokter::class,
            'idpet', // Foreign key on temu_dokter table
            'idreservasi_dokter', // Foreign key on rekam_medis table
            'idpet', // Local key on pet table
            'idreservasi_dokter' // Local key on temu_dokter table
        );
    }

    public function scopeWithRelations($query)
    {
        return $query->with([
            'pemilik.user',
            'rasHewan.jenisHewan'
        ]);
    }

    public function getGenderLabelAttribute()
    {
        return $this->jenis_kelamin === 'J' ? 'Jantan' : 'Betina';
    }

    public function getAgeAttribute()
    {
        if (!$this->tanggal_lahir) {
            return null;
        }

        $birthDate = \Carbon\Carbon::parse($this->tanggal_lahir);
        $now = \Carbon\Carbon::now();
        
        return $birthDate->diffInYears($now);
    }

    public function hasMedicalRecords()
    {
        return $this->temuDokter()->exists();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pet) {
            $temuDokterRecords = $pet->temuDokter()->get();

            foreach ($temuDokterRecords as $temuDokter) {
                $rekamMedisRecords = DB::table('rekam_medis')
                    ->where('idreservasi_dokter', $temuDokter->idreservasi_dokter)
                    ->get();

                foreach ($rekamMedisRecords as $rekamMedis) {
                    DB::table('detail_rekam_medis')
                        ->where('idrekam_medis', $rekamMedis->idrekam_medis)
                        ->delete();
                }

                DB::table('rekam_medis')
                    ->where('idreservasi_dokter', $temuDokter->idreservasi_dokter)
                    ->delete();

                $temuDokter->delete();
            }
        });
    }
}
