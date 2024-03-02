<?php

namespace App\Repositories;

use App\Models\Resident;

class ResidentRepository
{

    /**
     * Update an existing resident or create a new resident.
     *
     * @param  array  $attributes  The attributes to find or create the resident.
     * @param  array  $values  The values to update or create the resident.
     * @return \App\Models\Resident  The resident that was updated or created.
     */
    public function updateOrCreate(array $attributes, array $values): Resident
    {
        return Resident::updateOrCreate($attributes, $values);
    }
}
