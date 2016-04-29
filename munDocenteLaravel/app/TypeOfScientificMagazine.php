<?php

namespace MunDocente;

use Illuminate\Database\Eloquent\Model;

class TypeOfScientificMagazine extends Model
{
    protected $table = 'type_of_scientific_magazines';

    public function scientificMagazines()
    {
    	return $this->hasMany(Publication::class);
    }
}
