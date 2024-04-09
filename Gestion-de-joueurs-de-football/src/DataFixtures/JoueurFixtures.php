<?php
namespace App\DataFixtures;


use App\Entity\Club;
use App\Entity\Joueur;
use App\Entity\National;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class JoueurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // créer des equipes nationaux
        for($k=1; $k<=5; $k++){
            $national = new National();
            $national -> setNom($faker->country())
                      -> setDescription($faker->paragraph());

            $manager->persist($national);

            // créer 3 clubs
            for($i=0; $i<=3; $i++){
                $club = new Club();
                $club->setNomClub($faker->sentence())
                    ->setPays($faker->sentence());

                $manager->persist($club);

                // créer des joueurs
                for($j =1; $j <= 10; $j++){
                    $joueur = new Joueur();
                    $joueur ->setNom($faker->name())
                            ->setDateDeNaissance($faker->dateTime())
                            ->setNationalite($faker->country())
                            ->setParcours($faker->paragraph())
                            ->setNombreDeBut($faker->numberBetween($min = 0, $max = 1000))
                            ->setClub($club)
                            ->setNational($national);

                    $manager->persist($joueur);
                }
            }
        }
        $manager->flush();
    }
}
