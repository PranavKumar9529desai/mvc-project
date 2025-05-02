<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Batch;

class StageRecord extends Model
{
    protected $fillable = ['batch_id', 'stage', 'notes', 'completion_date'];

    protected $casts = [
        'completion_date' => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}
