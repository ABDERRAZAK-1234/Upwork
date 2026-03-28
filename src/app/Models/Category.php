<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function missions()
    {
        return $this->hasMany(Mission::class);
    }
}
