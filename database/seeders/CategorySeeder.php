<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Plomberie',                   'slug' => 'plomberie',     'icon' => '🔧', 'order' => 1],
            ['name' => 'Électricité',                 'slug' => 'electricite',   'icon' => '⚡', 'order' => 2],
            ['name' => 'Réparation électroménager',   'slug' => 'electromenager','icon' => '🔌', 'order' => 3],
            ['name' => 'Construction/BTP',            'slug' => 'construction',  'icon' => '🏗️', 'order' => 4],
            ['name' => 'Peinture',                    'slug' => 'peinture',      'icon' => '🎨', 'order' => 5],
            ['name' => 'Zellige/Marbre',              'slug' => 'zellige',       'icon' => '🪨', 'order' => 6],
            ['name' => 'Menuiserie',                  'slug' => 'menuiserie',    'icon' => '🪚', 'order' => 7],
            ['name' => 'Femme de ménage',             'slug' => 'menage',        'icon' => '🧹', 'order' => 8],
            ['name' => 'Transport',                   'slug' => 'transport',     'icon' => '🚚', 'order' => 9],
            ['name' => 'Jardinage',                   'slug' => 'jardinage',     'icon' => '🌿', 'order' => 10],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
