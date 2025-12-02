<?php

namespace App\Repositories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;

class CountryRepo
{

    public function getAllCountries():Collection
    {
        return Country::all();
    }

    public function getCountryById(int $id): Country
    {
        return Country::findOrFail($id);
    }

    // public function deleteById(int $id):bool
    // {
    //     $country = $this->getCountryById($id);
    //     return $country->delete();
    // }

    // public function updateCountruById(int $id, array $data)
    // {
    //     $country = $this->getCountryById($id);
    //     return $country->update($data);
    // }
}