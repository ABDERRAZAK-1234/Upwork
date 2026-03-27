<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            "Développement Web",
            "Développement Mobile",
            "Développement Desktop",
            "Full-Stack",
            "DevOps",
            "UI/UX"
        ];

        foreach ($categories as $cat) {
            Category::create(["name" => $cat]);
        }
    }
}
