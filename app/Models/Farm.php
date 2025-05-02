<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Batch;

class Farm extends Model
{
    protected $fillable = ['name', 'owner_id', 'location'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }
}
