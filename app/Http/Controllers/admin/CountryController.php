<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    private CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function index(){
        $allCountries = $this->countryService->getAllCities();
        return view('pages.admin.country.index',compact('allCountries'));
    }
        public function create(){
        return view('pages.admin.country.create');
    }
        public function edit(){
        return view('pages.admin.country.edit');
    }
}
