<?php

namespace App\Models;

use App\Enums\PatientType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory, SoftDeletes;


    protected $casts = [
        'type' => PatientType::class,
        'approved_at' => 'datetime',
        'is_approved' => 'boolean',
    ];


    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    /**
     * Scope a query to only include patients of the given type.
     *
     * @param Builder $query
     * @param PatientType $type
     *
     * @return void
     */
    public function scopeOfType(Builder $query, PatientType $type): void
    {
        $query->where('type', $type);
    }

    public function approve(): void
    {
        $this->update([
           'is_approved' => true,
           'approved_at' => $this->freshTimestamp(),
        ]);
    }
}
