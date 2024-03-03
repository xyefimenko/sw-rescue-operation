<?php

namespace App\Services;

use App\Http\Requests\PlanetStoreRequest;
use App\Http\Requests\ResidentStoreRequest;
use App\Http\Requests\SpecieStoreRequest;
use App\Models\Planet;
use App\Models\Resident;
use App\Models\Specie;
use App\Repositories\PlanetRepository;
use App\Repositories\ResidentRepository;
use App\Repositories\SpeciesRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlanetResidentSyncService
{

    //TODO Add hash to compare coming data and skip if it's the same

    /**
     * The URL of the Star Wars API planets.
     *
     * @var string
     */
    const API_URL = 'https://swapi.py4e.com/api/planets/';

    /**
     * The PlanetRepository instance.
     *
     * @var PlanetRepository
     */
    private $planetRepository;

    /**
     * The ResidentRepository instance.
     *
     * @var ResidentRepository
     */
    private $residentRepository;

    /**
     * The SpeciesRepository instance.
     *
     * @var SpeciesRepository
     */
    private $speciesRepository;

    /**
     * The GuzzleHttp\Client instance.
     *
     * @var Client
     */
    private $client;

    /**
     * Create a new PlanetResidentSyncService instance.
     *
     * @param  PlanetRepository  $planetRepository
     * @param  ResidentRepository  $residentRepository
     * @param  SpeciesRepository  $speciesRepository
     * @return void
     */
    public function __construct(
        PlanetRepository $planetRepository,
        ResidentRepository $residentRepository,
        SpeciesRepository  $speciesRepository
    )
    {
        $this->planetRepository = $planetRepository;
        $this->residentRepository = $residentRepository;
        $this->speciesRepository = $speciesRepository;
        $this->client = new Client();
    }

    /**
     * Sync data from the Star Wars API to the planets and residents tables.
     *
     * @return void
     */
    public function sync()
    {
        $url = self::API_URL;
        do {
            $response = $this->client->request('GET', $url);
            $data = json_decode($response->getBody(), true);
            $planetsData = $data['results'];

            $this->parsePlanetData($planetsData);

            $url = $data['next'];
        } while ($url);
    }

    /**
     * Parse planet data and create new Planet records.
     *
     * @param  array  $planetsData
     * @return void
     */
    public function parsePlanetData(array $planetsData)
    {
        foreach ($planetsData as $planetData) {
            if (!$this->validatePlanetData($planetData)) {
                continue;
            }

            $planet = $this->createPlanet($planetData);

            $promises = [];

            foreach ($planetData['residents'] as $residentUrl) {
                $promises[] = $this->client->getAsync($residentUrl)->then(
                    function ($response) use ($planet) {
                        $residentData = json_decode($response->getBody(), true);
                        if ($this->validateResidentData($residentData)) {
                            $resident = $this->createResident($residentData, $planet);
                            $this->parseSpeciesData($residentData['species'], $resident);
                        }
                    },
                    function ($exception) {
                        Log::error('Error fetching resident data: ' . $exception->getMessage());
                    }
                );
            }

            foreach ($promises as $promise) {
                $promise->wait();
            }
        }
    }

    /**
     * Parse species data and create new Species records.
     *
     * @param  array  $speciesUrls  The URLs of the species data.
     * @param  Resident  $resident  The resident associated with the species.
     * @return void
     */
    public function parseSpeciesData(array $speciesUrls, Resident $resident)
    {
        foreach ($speciesUrls as $speciesUrl) {
            $response = $this->client->request('GET', $speciesUrl);
            $speciesData = json_decode($response->getBody(), true);
            if ($this->validateSpeciesData($speciesData)) {
                $this->createSpecie($speciesData);
            }
        }
    }

    /**
     * Fetch species data and create a new Species record.
     *
     * @param  string|null  $speciesUrl  The URL of the species data.
     * @return Specie|null  The created Species record, or null if the species data could not be fetched or is invalid.
     */
    private function fetchAndCreateSpecies(?string $speciesUrl): ?Specie
    {
        if ($speciesUrl === null) {
            return null;
        }

        $speciesData = $this->getSpeciesData($speciesUrl);
        if (!$this->validateSpeciesData($speciesData)) {
            return null;
        }

        return $this->createSpecie($speciesData);
    }

    /**
     * Fetch species data from the Star Wars API.
     *
     * @param  string  $url  The URL of the species data.
     * @return array  The species data.
     */
    public function getSpeciesData(string $url): array
    {
        $response = $this->client->request('GET', $url);
        return json_decode($response->getBody(), true);
    }

    /**
     * Create a new Planet record or update an existing one.
     *
     * @param  array  $planetData
     * @return Planet
     */
    public function createPlanet(array $planetData): Planet
    {
        return $this->planetRepository->updateOrCreate(
            ['name' => $planetData['name']],
            [
                'rotation_period' => $planetData['rotation_period'],
                'orbital_period' => $planetData['orbital_period'],
                'diameter' => $planetData['diameter'],
                'climate' => $planetData['climate'],
                'gravity' => $planetData['gravity'],
                'terrain' => $planetData['terrain'],
                'surface_water' => $planetData['surface_water'],
                'population' => $planetData['population'],
                'url' => $planetData['url'],
            ]
        );
    }

    /**
     * Create a new Resident record or update an existing one.
     *
     * @param  array  $residentData
     * @param  Planet  $planet
     * @return Resident
     */
    public function createResident(array $residentData, Planet $planet): Resident
    {
        $species = $this->fetchAndCreateSpecies($residentData['species'][0] ?? null);

        return $this->residentRepository->updateOrCreate(
            [
                'name' => $residentData['name'],
                'url' => $residentData['url']
            ],
            [
                'planet_id' => $planet->id,
                'specie_id' => isset($species) ? $species->id : null,
                'name' => $residentData['name'],
                'height' => $residentData['height'],
                'mass' => $residentData['mass'],
                'hair_color' => $residentData['hair_color'],
                'skin_color' => $residentData['skin_color'],
                'eye_color' => $residentData['eye_color'],
                'birth_year' => $residentData['birth_year'],
                'gender' => $residentData['gender'],
            ]
        );
    }

    /**
     * Create a new Specie record or update an existing one.
     *
     * @param  array  $specieData
     * @return Specie
     */
    public function createSpecie(array $specieData): Specie
    {
        return $this->speciesRepository->updateOrCreate(
            ['url' => $specieData['url']],
            [
                'name' => $specieData['name'],
                'classification' => $specieData['classification'],
                'designation' => $specieData['designation'],
                'average_height' => $specieData['average_height'],
                'skin_colors' => $specieData['skin_colors'],
                'hair_colors' => $specieData['hair_colors'],
                'eye_colors' => $specieData['eye_colors'],
                'average_lifespan' => $specieData['average_lifespan'],
                'homeworld' => $specieData['homeworld'],
                'language' => $specieData['language'],
            ]
        );
    }

    /**
     * Validate the provided planet data.
     *
     * @param  array  $planetData
     * @return bool
     */
    public function validatePlanetData(array $planetData): bool
    {
        $request = new PlanetStoreRequest();
        $validator = Validator::make($planetData, $request->rules());

        if ($validator->fails()) {
            Log::error('Validation failed for planets data', $validator->errors()->toArray());
            return false;
        }

        return true;
    }

    /**
     * Validate the provided resident data.
     *
     * @param  array  $residentData
     * @return bool
     */
    public function validateResidentData(array $residentData): bool
    {
        $request = new ResidentStoreRequest();
        $validator = Validator::make($residentData, $request->rules());

        if ($validator->fails()) {
            Log::error('Validation failed for residents data', $validator->errors()->toArray());
            return false;
        }

        return true;
    }

    /**
     * Validate the provided species data.
     *
     * @param  array  $speciesData
     * @return bool
     */
    public function validateSpeciesData(array $speciesData): bool
    {
        $request = new SpecieStoreRequest();
        $validator = Validator::make($speciesData, $request->rules());

        if ($validator->fails()) {
            Log::error('Validation failed for species data', $validator->errors()->toArray());
            return false;
        }

        return true;
    }
}
