<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EbookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ebooks = [
            [
                'titre' => 'Réfléchissez et devenez riche',
                'slug' => 'reflechissez-devenez-riche',
                'auteur' => 'Napoleon Hill',
                'description' => 'Un classique intemporel du développement personnel. Napoleon Hill révèle les 13 principes du succès basés sur l\'étude de plus de 500 millionnaires. Apprenez à transformer vos pensées en richesse et à atteindre vos objectifs les plus ambitieux. Domaine public.',
                'prix' => 3500,
                'prix_promo' => 2500,
                'image' => 'reflechissez-riche.jpg',
                'fichier_pdf' => 'think-and-grow-rich.pdf',
                'pages' => 320,
                'category_id' => 1,
                'bestseller' => true,
                'nouveau' => false,
            ],
            [
                'titre' => 'La Richesse des Nations',
                'slug' => 'richesse-des-nations',
                'auteur' => 'Adam Smith',
                'description' => 'L\'œuvre fondatrice de l\'économie moderne. Adam Smith explique les principes du libre-échange, de la division du travail et du marché. Essentiel pour comprendre les bases de l\'économie et du commerce. Domaine public.',
                'prix' => 4500,
                'prix_promo' => 3500,
                'image' => 'richesse-nations.jpg',
                'fichier_pdf' => 'wealth-of-nations.pdf',
                'pages' => 450,
                'category_id' => 2,
                'bestseller' => true,
                'nouveau' => false,
            ],
            [
                'titre' => 'L\'Art de la Guerre',
                'slug' => 'art-de-la-guerre',
                'auteur' => 'Sun Tzu',
                'description' => 'Stratégies militaires appliquées au business et au marketing. Ce classique chinois enseigne la tactique, la stratégie et le leadership. Utilisé par les entrepreneurs et marketeurs du monde entier. Domaine public.',
                'prix' => 2500,
                'prix_promo' => null,
                'image' => 'art-guerre.jpg',
                'fichier_pdf' => 'art-of-war.pdf',
                'pages' => 180,
                'category_id' => 3,
                'bestseller' => false,
                'nouveau' => true,
            ],
            [
                'titre' => 'Le Guide du E-commerce',
                'slug' => 'guide-ecommerce',
                'auteur' => 'Creative Commons',
                'description' => 'Guide complet pour lancer votre boutique en ligne. De la création de votre site à la première vente, en passant par le marketing digital et la logistique. Stratégies éprouvées et outils gratuits. Licence Creative Commons.',
                'prix' => 5000,
                'prix_promo' => 3500,
                'image' => 'guide-ecommerce.jpg',
                'fichier_pdf' => 'ecommerce-guide.pdf',
                'pages' => 280,
                'category_id' => 4,
                'bestseller' => true,
                'nouveau' => true,
            ],
            [
                'titre' => 'Lean Startup',
                'slug' => 'lean-startup-guide',
                'auteur' => 'Eric Ries (extraits)',
                'description' => 'Méthodologie pour créer une startup innovante avec un minimum de ressources. Apprenez à valider vos idées rapidement, à pivoter si nécessaire et à construire un produit que les clients veulent vraiment. Version abrégée libre.',
                'prix' => 4000,
                'prix_promo' => 2500,
                'image' => 'lean-startup.jpg',
                'fichier_pdf' => 'lean-startup.pdf',
                'pages' => 250,
                'category_id' => 5,
                'bestseller' => false,
                'nouveau' => true,
            ],
            [
                'titre' => 'Comme un Homme Pense',
                'slug' => 'comme-homme-pense',
                'auteur' => 'James Allen',
                'description' => 'Un petit livre puissant sur le pouvoir de la pensée. James Allen explique comment nos pensées façonnent notre réalité et notre destinée. Court mais profond, ce livre a inspiré des millions de personnes. Domaine public.',
                'prix' => 2500,
                'prix_promo' => null,
                'image' => 'comme-homme-pense.jpg',
                'fichier_pdf' => 'as-man-thinketh.pdf',
                'pages' => 80,
                'category_id' => 1,
                'bestseller' => false,
                'nouveau' => false,
            ],
            [
                'titre' => 'Principes d\'Économie',
                'slug' => 'principes-economie',
                'auteur' => 'Alfred Marshall',
                'description' => 'Les fondamentaux de l\'économie et de la finance expliqués simplement. Offre, demande, marchés, investissement et gestion financière. Parfait pour comprendre les mécanismes économiques. Domaine public.',
                'prix' => 3500,
                'prix_promo' => 2500,
                'image' => 'principes-economie.jpg',
                'fichier_pdf' => 'principles-economics.pdf',
                'pages' => 380,
                'category_id' => 2,
                'bestseller' => true,
                'nouveau' => false,
            ],
            [
                'titre' => 'Marketing Digital Gratuit',
                'slug' => 'marketing-digital-gratuit',
                'auteur' => 'Open Source Marketing',
                'description' => 'Stratégies de marketing digital sans budget. SEO, réseaux sociaux, content marketing, email marketing et growth hacking. Outils gratuits et techniques éprouvées. Licence libre.',
                'prix' => 3000,
                'prix_promo' => null,
                'image' => 'marketing-gratuit.jpg',
                'fichier_pdf' => 'free-digital-marketing.pdf',
                'pages' => 200,
                'category_id' => 3,
                'bestseller' => false,
                'nouveau' => false,
            ],
            [
                'titre' => 'Vendre en Ligne',
                'slug' => 'vendre-en-ligne',
                'auteur' => 'E-commerce Collective',
                'description' => 'De zéro à la première vente en ligne. Création de boutique, choix des produits, paiement en ligne, livraison et service client. Guide pratique avec exemples concrets. Creative Commons.',
                'prix' => 4000,
                'prix_promo' => 3000,
                'image' => 'vendre-ligne.jpg',
                'fichier_pdf' => 'sell-online.pdf',
                'pages' => 240,
                'category_id' => 4,
                'bestseller' => false,
                'nouveau' => true,
            ],
            [
                'titre' => 'L\'Entrepreneur Moderne',
                'slug' => 'entrepreneur-moderne',
                'auteur' => 'Benjamin Franklin (adapté)',
                'description' => 'Sagesse intemporelle de Benjamin Franklin appliquée à l\'entrepreneuriat moderne. Principes de travail, d\'économie et de réussite qui restent valables aujourd\'hui. Domaine public adapté.',
                'prix' => 3500,
                'prix_promo' => 2500,
                'image' => 'entrepreneur-moderne.jpg',
                'fichier_pdf' => 'modern-entrepreneur.pdf',
                'pages' => 220,
                'category_id' => 5,
                'bestseller' => false,
                'nouveau' => false,
            ],
        ];

        foreach ($ebooks as $ebook) {
            \App\Models\Ebook::create($ebook);
        }
    }
}
