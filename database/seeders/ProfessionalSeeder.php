<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Professional;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProfessionalSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name'         => 'Mohammed Benali',
                'phone'        => '0661234567',
                'city'         => 'Casablanca',
                'category'     => 'plomberie',
                'description'  => 'Plombier professionnel avec 10 ans d\'expérience. Intervention rapide 7j/7, devis gratuit.',
                'skills'       => ['Plomberie', 'Sanitaire', 'Chauffage', 'Fuite d\'eau'],
                'languages'    => ['Arabe', 'Darija', 'Français'],
                'travel_cities'=> ['Casablanca', 'Mohammedia', 'Berrechid'],
                'availability' => 'available',
                'is_verified'  => true,
                'is_featured'  => true,
            ],
            [
                'name'         => 'Hassan Elmansouri',
                'phone'        => '0662345678',
                'city'         => 'Rabat',
                'category'     => 'electricite',
                'description'  => 'Électricien certifié spécialisé en installation et dépannage électrique résidentiel et commercial.',
                'skills'       => ['Électricité', 'Tableau électrique', 'Câblage', 'Domotique'],
                'languages'    => ['Arabe', 'Darija', 'Français'],
                'travel_cities'=> ['Rabat', 'Salé', 'Témara'],
                'availability' => 'available',
                'is_verified'  => true,
                'is_featured'  => true,
            ],
            [
                'name'         => 'Youssef Alaoui',
                'phone'        => '0663456789',
                'city'         => 'Marrakech',
                'category'     => 'peinture',
                'description'  => 'Peintre décorateur avec 8 ans d\'expérience. Spécialisé en peinture intérieure et extérieure.',
                'skills'       => ['Peinture intérieure', 'Peinture extérieure', 'Enduit', 'Ravalement'],
                'languages'    => ['Arabe', 'Darija'],
                'travel_cities'=> ['Marrakech', 'Agadir'],
                'availability' => 'available',
                'is_verified'  => false,
                'is_featured'  => false,
            ],
            [
                'name'         => 'Khalid Bouazza',
                'phone'        => '0664567890',
                'city'         => 'Casablanca',
                'category'     => 'electromenager',
                'description'  => 'Technicien en électroménager. Réparation de tous appareils: machines à laver, réfrigérateurs, climatiseurs.',
                'skills'       => ['Machine à laver', 'Réfrigérateur', 'Climatiseur', 'Four électrique'],
                'languages'    => ['Darija', 'Français'],
                'travel_cities'=> ['Casablanca', 'Ain Sebaa', 'Hay Hassani'],
                'availability' => 'busy',
                'is_verified'  => true,
                'is_featured'  => true,
            ],
            [
                'name'         => 'Ahmed Tazi',
                'phone'        => '0665678901',
                'city'         => 'Fès',
                'category'     => 'construction',
                'description'  => 'Maître d\'œuvre en construction et rénovation. Projets résidentiels et commerciaux.',
                'skills'       => ['Maçonnerie', 'Béton armé', 'Rénovation', 'Extension'],
                'languages'    => ['Arabe', 'Darija', 'Français'],
                'travel_cities'=> ['Fès', 'Meknès', 'Sefrou'],
                'availability' => 'available',
                'is_verified'  => true,
                'is_featured'  => false,
            ],
            [
                'name'         => 'Rachid Fassi',
                'phone'        => '0666789012',
                'city'         => 'Agadir',
                'category'     => 'zellige',
                'description'  => 'Artisan zelliger et marbrier avec 15 ans d\'expérience. Travaux de luxe garantis.',
                'skills'       => ['Zellige', 'Marbre', 'Mosaïque', 'Tadelakt'],
                'languages'    => ['Arabe', 'Darija', 'Français', 'Espagnol'],
                'travel_cities'=> ['Agadir', 'Taroudant', 'Tiznit'],
                'availability' => 'available',
                'is_verified'  => true,
                'is_featured'  => true,
            ],
            [
                'name'         => 'Nadia Benkirane',
                'phone'        => '0667890123',
                'city'         => 'Casablanca',
                'category'     => 'menage',
                'description'  => 'Service de ménage professionnel, ponctuelle et sérieuse. Disponible 6j/7.',
                'skills'       => ['Ménage', 'Repassage', 'Cuisine', 'Garde d\'enfants'],
                'languages'    => ['Darija', 'Français'],
                'travel_cities'=> ['Casablanca', 'Anfa', 'Maarif'],
                'availability' => 'available',
                'is_verified'  => false,
                'is_featured'  => false,
            ],
            [
                'name'         => 'Omar Hajji',
                'phone'        => '0668901234',
                'city'         => 'Tanger',
                'category'     => 'transport',
                'description'  => 'Transport de personnes et de marchandises. Camion et fourgon disponibles.',
                'skills'       => ['Transport', 'Déménagement', 'Livraison', 'Logistique'],
                'languages'    => ['Arabe', 'Darija', 'Français', 'Espagnol'],
                'travel_cities'=> ['Tanger', 'Tétouan', 'Larache', 'Ksar El Kébir'],
                'availability' => 'available',
                'is_verified'  => true,
                'is_featured'  => false,
            ],
            [
                'name'         => 'Hamid Lahnech',
                'phone'        => '0669012345',
                'city'         => 'Casablanca',
                'category'     => 'menuiserie',
                'description'  => 'Menuisier bois et aluminium. Fabrication sur mesure de cuisines, dressings et portes.',
                'skills'       => ['Menuiserie bois', 'Menuiserie alu', 'Cuisine sur mesure', 'Dressing'],
                'languages'    => ['Darija', 'Français'],
                'travel_cities'=> ['Casablanca', 'Ain Chock', "Ben M'sick"],
                'availability' => 'closed',
                'is_verified'  => false,
                'is_featured'  => false,
            ],
            [
                'name'         => 'Moussa Idrissi',
                'phone'        => '0660123456',
                'city'         => 'Rabat',
                'category'     => 'jardinage',
                'description'  => 'Paysagiste professionnel. Création et entretien de jardins, terrasses et espaces verts.',
                'skills'       => ['Jardinage', "Taille d'arbres", 'Gazon', 'Arrosage automatique'],
                'languages'    => ['Arabe', 'Darija', 'Français'],
                'travel_cities'=> ['Rabat', 'Salé', 'Hay Riad', 'Agdal'],
                'availability' => 'available',
                'is_verified'  => true,
                'is_featured'  => true,
            ],
            [
                'name'         => 'Karim Doukkali',
                'phone'        => '0670111222',
                'city'         => 'Casablanca',
                'category'     => 'plomberie',
                'description'  => 'Plombier chauffagiste, installation de chauffe-eaux et systèmes de chauffage central.',
                'skills'       => ['Chauffe-eau', 'Chauffage central', 'Plomberie', 'Soudure'],
                'languages'    => ['Darija', 'Français'],
                'travel_cities'=> ['Casablanca', 'Hay Mohammadi'],
                'availability' => 'available',
                'is_verified'  => false,
                'is_featured'  => false,
            ],
            [
                'name'         => 'Samir Ouazzani',
                'phone'        => '0671222333',
                'city'         => 'Marrakech',
                'category'     => 'electricite',
                'description'  => 'Installation électrique complète pour villas et appartements. Certifié Consuel.',
                'skills'       => ['Installation électrique', 'Armoire électrique', 'Éclairage', 'Solaire'],
                'languages'    => ['Arabe', 'Darija', 'Français', 'Anglais'],
                'travel_cities'=> ['Marrakech', 'Guéliz', 'Hivernage'],
                'availability' => 'available',
                'is_verified'  => true,
                'is_featured'  => false,
            ],
        ];

        $reviews = [
            ['reviewer_name' => 'Karim M.',   'rating' => 5, 'comment' => 'Excellent travail, très professionnel et ponctuel!'],
            ['reviewer_name' => 'Sara L.',    'rating' => 4, 'comment' => 'Bon service, je recommande.'],
            ['reviewer_name' => 'Mehdi B.',   'rating' => 5, 'comment' => 'Parfait! Résultat impeccable.'],
            ['reviewer_name' => 'Laila H.',   'rating' => 4, 'comment' => 'Très compétent, tarif correct.'],
            ['reviewer_name' => 'Amine R.',   'rating' => 3, 'comment' => 'Bon travail mais un peu en retard.'],
        ];

        foreach ($data as $item) {
            $category = Category::where('slug', $item['category'])->first();
            if (!$category) {
                continue;
            }

            $pro = Professional::create([
                'category_id'           => $category->id,
                'name'                  => $item['name'],
                'slug'                  => Str::slug($item['name']) . '-' . Str::random(6),
                'phone'                 => $item['phone'],
                'city'                  => $item['city'],
                'description'           => $item['description'],
                'skills'                => $item['skills'],
                'languages'             => $item['languages'],
                'travel_cities'         => $item['travel_cities'],
                'availability'          => $item['availability'],
                'is_verified'           => $item['is_verified'],
                'is_featured'           => $item['is_featured'],
                'total_views'           => rand(20, 800),
                'total_whatsapp_clicks' => rand(2, 80),
                'total_calls'           => rand(1, 40),
            ]);

            $count = rand(1, 3);
            foreach (array_slice($reviews, 0, $count) as $review) {
                Review::create([
                    'professional_id' => $pro->id,
                    'reviewer_name'   => $review['reviewer_name'],
                    'rating'          => $review['rating'],
                    'comment'         => $review['comment'],
                ]);
            }

            $pro->updateAverageRating();
        }
    }
}
