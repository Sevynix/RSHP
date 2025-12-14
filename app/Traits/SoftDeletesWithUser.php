<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

trait SoftDeletesWithUser
{
    use SoftDeletes;

    /**
     * Perform soft delete with user tracking
     * 
     * @return bool|null
     */
    public function delete()
    {
        // Set deleted_by before soft deleting
        $this->deleted_by = Auth::id();
        $this->save();
        
        // Perform soft delete
        return parent::delete();
    }

    /**
     * Restore the soft-deleted model
     * 
     * @return bool|null
     */
    public function restore()
    {
        // Clear deleted_by when restoring
        $this->deleted_by = null;
        
        // Perform restore
        return parent::restore();
    }

    public function deletedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by', 'iduser');
    }
}
