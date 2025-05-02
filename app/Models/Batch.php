<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Farm;
use App\Models\StageRecord;

class Batch extends Model
{
    protected $fillable = [
        'farm_id',
        'batch_number',
        'start_date',
        'end_date',
        'wool_type',
        'weight_kg',
        'status',
        'arrival_date',
        'notes'
    ];

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function stageRecords(): HasMany
    {
        return $this->hasMany(StageRecord::class);
    }
}
