<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;


    /**
     * __construct
     *
     * @param  UserPasswordHasherInterface $hascher
     * @return void
     */
    public function __construct(UserPasswordHasherInterface $hascher)
    {
        $this->hasher = $hascher;
    }




    /**
     * load
     *
     * @param  ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i < 50; $i++) {
            $user = new User();
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setEmail($faker->email);
            $password = $this->hasher->hashPassword($user, '12345678');
            $user->setPassword($password);
            $user->setAdresse($faker->address);
            $user->setCodePostal($faker->postcode);
            $user->setVille($faker->city);
            $user->setNumeroPortable($faker->phoneNumber);
            $user->setRoles(["ROLE_USER"]);

            $manager->persist($user);
        }
        $manager->flush();
    }
}
