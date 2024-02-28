<?php

namespace App\Services;

use App\Models\Planet;
use App\Models\Resident;
use GuzzleHttp\Client;

class PlanetResidentSyncService
{

    /**
     * The URL of the Star Wars API planets.
     *
     * @var string
     */
    const API_URL = 'https://swapi.py4e.com/api/planets/';

    /**
     * Sync data from the Star Wars API to the planets and residents tables.
     *
     * @return void
     */
    public function sync()
    {
        $client = new Client();

        // Fetch data for planets
        $response = $client->request('GET', self::API_URL);
        $planetsData = json_decode($response->getBody(), true)['results'];

        // Parse planet data and create new Planet records
        $planets = $this->parsePlanetData($planetsData);

        // Parse resident data, create new Resident records, and associate them with the planets
        foreach ($planets as $planet) {
            $this->parseResidentData($planetsData['residents'], $planet);
        }
    }

    /**
     * Parse planet data and create new Planet records.
     *
     * @param  array  $planetsData
     * @return array
     */
    public function parsePlanetData(array $planetsData)
    {
        $planets = [];

        foreach ($planetsData as $planetData) {
            $planet = Planet::create([
                'name' => $planetData['name'],
            ]);

            $planets[] = $planet;
        }

        return $planets;
    }

    /**
     * Parse resident data, create new Resident records, and associate them with a Planet.
     *
     * @param  array  $residentUrls
     * @param  Planet  $planet
     * @return void
     */
    public function parseResidentData(array $residentUrls, Planet $planet)
    {
        $client = new Client();

        foreach ($residentUrls as $residentUrl) {
            $response = $client->request('GET', $residentUrl);
            $residentData = json_decode($response->getBody(), true);

            Resident::create([
                'planet_id' => $planet->id,
                'name' => $residentData['name'],
            ]);
        }
    }
}
