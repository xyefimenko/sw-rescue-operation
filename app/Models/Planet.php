<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'rotation_period',
        'orbital_period',
        'diameter',
        'climate',
        'gravity',
        'terrain',
        'surface_water',
        'population',
        'url',
    ];

    /**
     * Get the residents for the planet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    /**
     * Get the logbooks for the planet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }
}
