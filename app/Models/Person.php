<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'date_of_birth',
        'picture',
        'biography',
        'father_id',
        'mother_id',
        'phone_number',
        'email',
        'address',
        'is_deceased',
        'date_of_death',
    ];

    public function father()
    {
        return $this->belongsTo(Person::class, 'father_id');
    }

    public function mother()
    {
        return $this->belongsTo(Person::class, 'mother_id');
    }

    public function children()
    {
        return $this->hasMany(Person::class, 'father_id')->orWhere('mother_id', $this->id);
    }
}
