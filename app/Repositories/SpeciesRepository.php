<?php

namespace App\Repositories;

use App\Models\Specie;

class SpeciesRepository
{

    /**
     * Update an existing specie or create a new specie.
     *
     * @param  array  $attributes  The attributes to find or create the specie.
     * @param  array  $values  The values to update or create the specie.
     * @return \App\Models\Specie  The specie that was updated or created.
     */
    public function updateOrCreate(array $attributes, array $values): Specie
    {
        return Specie::updateOrCreate($attributes, $values);
    }
}
