<?php

namespace App\Services;

use App\Http\Requests\PlanetStoreRequest;
use App\Http\Requests\ResidentStoreRequest;
use App\Models\Planet;
use App\Models\Resident;
use App\Repositories\PlanetRepository;
use App\Repositories\ResidentRepository;
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
     * @return void
     */
    public function __construct(PlanetRepository $planetRepository, ResidentRepository $residentRepository)
    {
        $this->planetRepository = $planetRepository;
        $this->residentRepository = $residentRepository;
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
                            $this->createResident($residentData, $planet);
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
        return $this->residentRepository->updateOrCreate(
            [
                'name' => $residentData['name'],
                'url' => $residentData['url']
            ],
            [
                'planet_id' => $planet->id,
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
}
