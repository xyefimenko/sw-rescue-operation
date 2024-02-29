<?php

namespace App\Repositories;

use App\Models\Planet;

class PlanetRepository
{
    public function updateOrCreate(array $attributes, array $values): Planet
    {
        return Planet::updateOrCreate($attributes, $values);
    }
}
