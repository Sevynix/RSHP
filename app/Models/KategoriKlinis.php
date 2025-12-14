<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeletesWithUser;

class KategoriKlinis extends Model
{
    use SoftDeletesWithUser;

    protected $table = 'kategori_klinis';
    protected $primaryKey = 'idkategori_klinis';
    public $timestamps = false;
    
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'nama_kategori_klinis',
        'deleted_by',
    ];

    public function kodeTindakanTerapi()
    {
        return $this->hasMany(KodeTindakanTerapi::class, 'idkategori_klinis', 'idkategori_klinis');
    }
}
