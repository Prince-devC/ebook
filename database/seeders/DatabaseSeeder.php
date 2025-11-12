<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un utilisateur admin pour Filament
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@ebook.com',
            'password' => bcrypt('password'),
        ]);

        // Appeler les seeders
        $this->call([
            CategorySeeder::class,
            EbookSeeder::class,
        ]);
    }
}
