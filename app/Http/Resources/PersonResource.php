<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'date_of_birth' => $this->date_of_birth,
            'picture' => $this->picture ? asset($this->picture) : null,
            'biography' => $this->biography,
            'father_id' => $this->father_id,
            'father_name' => $this->father ? $this->father->full_name : null,
            'mother_id' => $this->mother_id,
            'mother_name' => $this->mother ? $this->mother->full_name : null,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'address' => $this->address,
            'is_deceased' => $this->is_deceased,
            'date_of_death' => $this->date_of_death,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
