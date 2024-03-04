<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $userPassword;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher){
        $this->userPassword=$userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin  ->setNom("Admin")
                ->setPrenom("Root")
                ->setEmail("admin@gmail.com")
                ->setIsVerified(true)
                ->setRoles(['ROLE_ADMIN'])
                ->setPassword($this->userPassword->hashPassword(
                    $admin,
                    "voyage59"
                ))
                ;
            $manager->persist($admin);

        $manager->flush();
    }
}
