<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Room;
use App\Entity\User;
use App\Entity\Movie;
use App\Entity\Session;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Ajout de toute les fixture

        $faker=Factory::create("fr_FR");

        $lesFilms = $this->chargeFichier("film.csv");

        foreach ($lesFilms as $value) {
            $film = new Movie();
            $film       ->setId(intval($value[0]))
                        ->setNom($value[1])
                        ->setDescription($value[2])
                        ->setAffiche($value[3])
                        ->setDate(intval($value[4]))
                        ->setDuree($value[5]);
                        $manager->persist($film);
            $this->addReference("film".$film->getId(),$film);
        }

        $lesCategories = $this->chargeFichier("categorie.csv");

        foreach ($lesCategories as $value) {
            $Categorie = new Category();
            $Categorie  ->setId(intval($value[0]))
                        ->setNom($value[1])
                        ->setDescription($value[2]);
            $manager->persist($Categorie);
            $this->addReference("categorie".$Categorie->getId(),$Categorie);
        }

        $lesSalles = $this->chargeFichier("salle.csv");

        foreach ($lesSalles as $value) {
            $Salle = new Room();
            $Salle  ->setId(intval($value[0]))
                        ->setNom($value[1])
                        ->setCapaciter($value[2]);
            $manager->persist($Salle);
            $this->addReference("salle".$Salle->getId(),$Salle);
        }

        $lesSeances = $this->chargeFichier("seance.csv");

        foreach ($lesSeances as $value) {
            $Seance = new Session();
            $Seance  ->setId(intval($value[0]))
                        ->setDate($value[1])
                        ->setHeure($value[2]);
            $manager->persist($Seance);
            $this->addReference("seance".$Seance->getId(),$Seance);
        }
/*
        $admin = new User();
        $admin  ->setNom("Admin")
                ->setPrenom("Root")
                ->setEmail("admin@gmail.com")
                ->setIsVerified(true)
                ->setRoles(['ROLE_ADMIN'])
                ->setPassword( $this->userPassword->hashPassword(
                    $admin,
                    "voyage59"
                ));

*/
        $manager->flush();
    }

    public function chargeFichier($fichier) {
        $fichierCsv=fopen(__DIR__."/". $fichier ,"r");
        while (!feof($fichierCsv)) {
            $data[]=fgetcsv($fichierCsv);
        }
        fclose($fichierCsv);
        return $data;
    }
} 
