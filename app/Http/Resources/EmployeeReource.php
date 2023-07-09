<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeReource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $request->id,
            'fname' => $request->first_name,
            'lname' => $request->last_name,
            'address' => $request->address,
            'countryId' => $request->country_id,
            'stateId' => $request->state_id,
            'cityId' => $request->city_id,
            'departmentId' => $request->department_id,
            'zipCode' => $request->zip_code,
            'birthDate' => $request->birth_date,
            'dateHired' => $request->date_hired,
        ];
    }
}
