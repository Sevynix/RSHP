<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SoftDeletesWithUser;

class Pemilik extends Model
{
    use SoftDeletesWithUser;

    protected $table = 'pemilik';
    protected $primaryKey = 'idpemilik';
    public $timestamps = false;
    
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'iduser',
        'no_wa',
        'alamat',
        'deleted_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'idpemilik', 'idpemilik');
    }
}
