<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorAndBookSeeder extends Seeder
{
    public function run()
    {
        // Gerar 100 autores cada um com 10 livros
        Author::factory(10)->hasBooks(3)->create();
    }
}

