<?php

namespace App\Http\Controllers;

use App\Repositories\PlanetRepository;
use App\Http\Requests\PlanetSearchRequest;

class PlanetController extends Controller
{

    /**
     * Display a listing of the planets.
     *
     * @param  PlanetSearchRequest  $request
     * @param  PlanetRepository  $planetRepository
     * @return \Illuminate\View\View
     */
    public function index(PlanetSearchRequest $request, PlanetRepository $planetRepository)
    {
        $planets = $planetRepository->getFilteredPlanets($request->validated());

        return view('planets.index', compact('planets'));
    }
}
