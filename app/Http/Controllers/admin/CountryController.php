<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Country\CreateCountryRequest;
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

    public function save(CreateCountryRequest $request)
    {
        try{
            $country = $this->countryService->createCountry($request->validated());
            return redirect()->route('admin.country')->with('success','Страна с названием "' . $country->name . '" успешно добавлена в базу');
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $country = $this->countryService->getCountryById($id);
        return view('pages.admin.country.edit', compact('country'));
    }
}
