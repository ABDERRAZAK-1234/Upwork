<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = [
        "client_id",
        "category_id",
        "title",
        "description",
        "budget",
        "duration",
        "type",
        "status"
    ];

    public function client()
    {
        return $this->belongsTo(User::class, "client_id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
