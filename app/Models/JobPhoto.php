<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPhoto extends Model
{
    protected $fillable = ['job_id','uploaded_by','url','thumb_url','meta'];

    public function job()
    {
        return $this->belongsTo(TechnicianJob::class, 'job_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
