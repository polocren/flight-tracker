<?php

namespace App\Http\Controllers;

use App\Models\Aircraft;
use Illuminate\Http\Request;

class AircraftController extends Controller
{
    public function index()
    {
        $aircraft = Aircraft::all();
        return response()->json($aircraft);
    }

    public function show(Aircraft $aircraft)
    {
        return response()->json($aircraft);
    }
}