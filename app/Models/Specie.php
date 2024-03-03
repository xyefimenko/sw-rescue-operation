<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'classification',
        'designation',
        'average_height',
        'skin_colors',
        'hair_colors',
        'eye_colors',
        'average_lifespan',
        'homeworld',
        'language',
        'url',
    ];

    /**
     * Get the logbooks for the planet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function residents()
    {
        return $this->hasMany(Resident::class);
    }
}
