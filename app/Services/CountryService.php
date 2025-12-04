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

    public function getCountryByName(string $name)
    {
        return $this->countryRepo->getCountryByName($name);
    }

    public function createCountry(array $data):Country
    {
        $existingCountry = $this->countryRepo->getCountryByName($data['name']);

        if($existingCountry){
            throw new \Exception('Страна с таким названием уже существует');
        }

        return $this->countryRepo->createCountry($data);
    }

    public function deleteCountryById(int $id):bool
    {
        return $this->countryRepo->deleteCountryById($id);
    }
    // public function updateCountryById(int $id, array $data)
    // {
    //    return $this->countryRepo->updateCountruById($id, $data);
    // }
}