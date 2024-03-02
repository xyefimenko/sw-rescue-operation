<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogbookStoreRequest;
use App\Models\Logbook;

class LogbookController extends Controller
{

    /**
     * Store a newly created logbook entry in storage.
     *
     * @param  LogbookStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LogbookStoreRequest $request)
    {
        $logbook = Logbook::create($request->validated());
        return response()->json($logbook, 201);
    }
}
