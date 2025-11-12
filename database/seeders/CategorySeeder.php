<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Développement Personnel',
                'slug' => 'developpement-personnel',
                'description' => 'Livres pour améliorer votre mindset et vos compétences personnelles',
            ],
            [
                'nom' => 'Trading & Finance',
                'slug' => 'trading-finance',
                'description' => 'Guides pour maîtriser les marchés financiers et le trading',
            ],
            [
                'nom' => 'Marketing Digital',
                'slug' => 'marketing-digital',
                'description' => 'Stratégies et techniques de marketing en ligne',
            ],
            [
                'nom' => 'E-commerce',
                'slug' => 'e-commerce',
                'description' => 'Tout pour réussir dans la vente en ligne',
            ],
            [
                'nom' => 'Entrepreneuriat',
                'slug' => 'entrepreneuriat',
                'description' => 'Créer et développer votre business',
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
