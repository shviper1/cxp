<?php

namespace App\Http\Controllers;

use App\Models\Country;

class HomePageController extends Controller
{
    public function index()
    {
        // eager load states and cities
        $countries = Country::with('states.cities')->get();

        return view('welcome', compact('countries'));
    }
}
