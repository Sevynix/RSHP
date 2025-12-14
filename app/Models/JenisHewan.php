<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeletesWithUser;

class JenisHewan extends Model
{
    use SoftDeletesWithUser;

    protected $table = 'jenis_hewan';
    protected $primaryKey = 'idjenis_hewan';
    public $timestamps = false;
    
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'nama_jenis_hewan',
        'deleted_by',
    ];

    public function rasHewan()
    {
        return $this->hasMany(RasHewan::class, 'idjenis_hewan', 'idjenis_hewan');
    }
}
