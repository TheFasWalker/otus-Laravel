<?php 

namespace App\Services;

use App\Models\Country;
use App\Repositories\CountryRepo;

class CountryService 
{
    public function __construct(private CountryRepo $countryRepo)
    {

    }

    public function getAllCities()
    {
        return $this->countryRepo->getAllCountries();
    }

    public function getCountryById(int $id):Country
    {
        return $this->countryRepo->getCountryById($id);
    }

    // public function updateCountryById(int $id, array $data)
    // {
    //    return $this->countryRepo->updateCountruById($id, $data);
    // }
}