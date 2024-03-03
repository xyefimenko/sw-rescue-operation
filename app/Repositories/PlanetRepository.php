<?php

namespace App\Repositories;

use App\Models\Planet;
use App\Models\Resident;
use App\Models\Specie;
use Illuminate\Support\Facades\DB;

class PlanetRepository
{

    /**
     * Update an existing planet or create a new planet.
     *
     * @param  array  $attributes  The attributes to find or create the planet.
     * @param  array  $values  The values to update or create the planet.
     * @return \App\Models\Planet  The planet that was updated or created.
     */
    public function updateOrCreate(array $attributes, array $values): Planet
    {
        return Planet::updateOrCreate($attributes, $values);
    }

    /**
     * Get the paginated planets with optional search parameters.
     *
     * @param  array  $validated
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getFilteredPlanets(array $validated)
    {
        $query = Planet::query();

        if (isset($validated['planet_search'])) {
            $search = $validated['planet_search'];
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('diameter', 'like', "%{$search}%")
                ->orWhere('rotation_period', 'like', "%{$search}%")
                ->orWhere('climate', 'like', "%{$search}%")
                ->orWhere('terrain', 'like', "%{$search}%")
                ->orWhere('population', 'like', "%{$search}%")
                ->orWhere('gravity', 'like', "%{$search}%");
        }

        if (isset($validated['diameter'])) {
            $query->where('diameter', $validated['diameter']);
        }

        if (isset($validated['rotation_period'])) {
            $query->where('rotation_period', $validated['rotation_period']);
        }

        if (isset($validated['gravity'])) {
            $query->where('gravity', $validated['gravity']);
        }

        return $query->paginate(10);
    }

    /**
     * Get the aggregated data about the planets.
     *
     * @return array
     */
    public function getAggregatedData()
    {
        // List of names of 10 largest planets
        $largestPlanets = Planet::where('diameter', '!=', 'unknown')
            ->orderByRaw('CAST(diameter AS UNSIGNED) DESC')
            ->take(10)
            ->get(['name', 'diameter']);

        // Distribution of the terrain
        $terrainDistribution = Planet::all()
            ->flatMap(function ($planet) {
                return array_map('trim', explode(',', $planet->terrain));
            })
            ->countBy()
            ->toArray();


        // Total number of residents
        $totalResidents = Resident::count();

        // Distribution of the species living in all planets
        $speciesDistribution = DB::table('residents')
            ->select('specie_id', DB::raw('count(*) as total'))
            ->groupBy('specie_id')
            ->get()
            ->mapWithKeys(function ($item) use ($totalResidents) {
                $speciesName = $item->specie_id ? Specie::find($item->specie_id)->name : 'Unknown';
                $percentage = round(($item->total / $totalResidents) * 100, 2);
                return [$speciesName => ['total' => $item->total, 'percentage' => $percentage]];
            });

        return [
            'largest_planets' => $largestPlanets,
            'terrain_distribution' => $terrainDistribution,
            'species_distribution' => $speciesDistribution,
        ];
    }
}
