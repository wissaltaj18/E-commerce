<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    /**
     * @var list<string>
     */
    private const CATEGORIES = [
        'Electronique',
        'Vetements',
        'Maison et Jardin',
        'Sports',
        'Beaute',
    ];

    /**
     * @var list<array{string, int, string, string, int}>
     */
    private const PRODUCTS = [
        ['Smartphone Samsung Galaxy A54', 3499, 'Ecran 6.4 pouces, 128Go, 5G, batterie 5000mAh', 'smartphone.jpg', 0],
        ['Ecouteurs Bluetooth JBL', 599, 'Son premium, autonomie 40h, reduction de bruit active', 'ecouteurs.jpg', 0],
        ['Laptop HP 15 pouces Intel i5', 7999, '8Go RAM, 512Go SSD, Windows 11 inclus', 'laptop.jpg', 0],
        ['Montre connectee Xiaomi', 899, 'Suivi sante, GPS integre, etanche, 7 jours autonomie', 'montre.jpg', 0],
        ['T-shirt Premium Coton', 149, '100% coton bio, disponible en 5 couleurs', 'tshirt.jpg', 1],
        ['Jean Slim Homme', 399, 'Coupe moderne, denim stretch, tailles 28 a 42', 'jean.jpg', 1],
        ['Robe ete Femme', 299, 'Legere et elegante, parfaite pour la saison estivale', 'robe.jpg', 1],
        ['Veste Impermeable', 699, 'Protection pluie garantie, respirante, capuche amovible', 'veste.jpg', 1],
        ['Lampe LED Bureau', 249, 'Lumiere reglable 3 modes, port USB integre', 'lampe.jpg', 2],
        ['Aspirateur Robot', 1899, 'Nettoyage automatique programmable, cartographie WiFi', 'aspirateur.jpg', 2],
        ['Plante Succulente', 89, 'Facile a entretenir, ideale pour bureau ou salon', 'plante.jpg', 2],
        ['Tapis de Yoga', 199, 'Antiderapant, eco-friendly, epaisseur 6mm', 'yoga.jpg', 3],
        ['Ballon de Football', 149, 'Taille 5 officielle, cuir synthetique', 'ballon.jpg', 3],
        ['Gants de Boxe', 349, 'Cuir veritable, protection maximale, 10oz', 'gants.jpg', 3],
        ['Creme Hydratante Visage', 199, '100% naturelle, SPF30, peaux sensibles', 'creme.jpg', 4],
        ['Parfum Oriental', 599, 'Notes de bois et epices, flacon 50ml', 'parfum.jpg', 4],
    ];

    public function load(ObjectManager $manager): void
    {
        $categories = $this->loadCategories($manager);
        $this->loadProducts($manager, $categories);

        $manager->flush();
    }

    /**
     * @return list<Category>
     */
    private function loadCategories(ObjectManager $manager): array
    {
        $categories = [];

        foreach (self::CATEGORIES as $name) {
            $category = new Category();
            $category->setName($name);

            $manager->persist($category);
            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * @param list<Category> $categories
     */
    private function loadProducts(ObjectManager $manager, array $categories): void
    {
        foreach (self::PRODUCTS as [$name, $price, $description, $image, $categoryIndex]) {
            $product = new Product();
            $product->setName($name);
            $product->setPrice($price);
            $product->setDescription($description);
            $product->setImage($image);
            $product->setCategory($categories[$categoryIndex]);

            $manager->persist($product);
        }
    }
}