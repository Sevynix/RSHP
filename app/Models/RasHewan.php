<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeletesWithUser;

class RasHewan extends Model
{
    use SoftDeletesWithUser;

    protected $table = 'ras_hewan';
    protected $primaryKey = 'idras_hewan';
    public $timestamps = false;
    
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'nama_ras',
        'idjenis_hewan',
        'deleted_by',
    ];

    public function jenisHewan()
    {
        return $this->belongsTo(JenisHewan::class, 'idjenis_hewan', 'idjenis_hewan');
    }
}
