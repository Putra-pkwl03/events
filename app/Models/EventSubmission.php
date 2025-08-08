<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSubmission extends Model
{
    protected $fillable = ['submission_id', 'submitted_at', 'is_present', 'token', 'qr_code_path' ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    public function answers()
    {
        return $this->hasMany(EventSubmissionAnswer::class, 'submission_id');
    }
    
}
