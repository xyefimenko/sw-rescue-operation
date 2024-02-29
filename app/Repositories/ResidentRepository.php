<?php

namespace App\Repositories;

use App\Models\Resident;

class ResidentRepository
{
    public function updateOrCreate(array $attributes, array $values): Resident
    {
        return Resident::updateOrCreate($attributes, $values);
    }
}
