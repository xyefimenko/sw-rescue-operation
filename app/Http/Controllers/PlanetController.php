<?php

namespace App\Http\Controllers;

use App\Repositories\PlanetRepository;
use App\Http\Requests\PlanetSearchRequest;
use Illuminate\Support\Facades\Artisan;

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

    /**
     * Trigger the Artisan command to sync planets and residents.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function triggerSyncCommand()
    {
        Artisan::call('sync:planets-and-residents');

        return redirect()->route('planets');
    }

    /**
     * Get the aggregated data about the planets.
     *
     * @param  PlanetRepository  $planetRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAggregatedData(PlanetRepository $planetRepository)
    {
        $data = $planetRepository->getAggregatedData();

        return response()->json($data);
    }
}
