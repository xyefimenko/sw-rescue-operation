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
        $validated = $request->validated();
        $logbooks = [];
        foreach ($validated as $logbookData) {
            $logbooks[] = Logbook::create($logbookData);
        }
        return response()->json($logbooks, 201);
    }
}
