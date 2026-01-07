<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Country\CreateCountryRequest;
use App\Http\Requests\Country\UpdateCountryRequest;
use App\Services\CountryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

    public function store(CreateCountryRequest $request)
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

    public function delete($id)
    {

        $deleteCountry = $this->countryService->deleteCountryById($id);
        if($deleteCountry){
            return redirect()->route('admin.country')->with('success','Старни успешно удалена');
        }

        return redirect()->back()->with('error', 'Удаляемая старни не найдена');
    }
    public function update(int $id,UpdateCountryRequest $request)
    {

        $updatedCountry = $this->countryService->updateCountryById($id,$request->validated());
        if($updatedCountry){
            return redirect()->back()->with('success','Обноеление данных прошло успешно');
        }

        return redirect()->back()->with('errors','Обновление данных прошло неудачно')->withInput();
        }
}
