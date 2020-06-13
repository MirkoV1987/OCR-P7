<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        
        $faker = Faker\Factory::create('fr_FR');

        $clients = [];

        // ================================
        // ==========Customers=============
        //===============================//

        //---------Orange------------
        $client = new Client();
        $client->setName('Orange');
        $client->setEmail('client1@gmail.com');
        $client->setPassword($this->passwordEncoder->encodePassword($client, 'Client1'));
        $client->setRoles(['ROLE_ADMIN']);

        $manager->persist($client);
        $clients[] = $client;

        //--------Bouygues-----------
        $client = new Client();
        $client->setName('Bouygues');
        $client->setEmail('client2@gmail.com');
        $client->setPassword($this->passwordEncoder->encodePassword($client, 'Client2'));
        $client->setRoles(['ROLE_ADMIN']);

        $manager->persist($client);
        $clients[] = $client;

        //----------SFR--------------
        $client = new Client();
        $client->setName('SFR');
        $client->setEmail('client3@gmail.com');
        $client->setPassword($this->passwordEncoder->encodePassword($client, 'Client3'));
        $client->setRoles(['ROLE_ADMIN']);

        $manager->persist($client);
        $clients[] = $client;


        // ================================
        // ============Users===============
        //===============================//
        
        for($i=1; $i<=14; $i++)
        {
            $user = new User();
            $user->setUsername($faker->username);
            $user->setEmail($faker->safeEmail);
            $user->setPhone($faker->randomNumber($nbDigits = 6, $strict = true));
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'test'));
            $user->setClient($faker->randomElement($clients));
            $user->setRoles(['ROLE_USER']);
            $user->setDateAdd($faker->dateTimeBetween($startDate = '-8 months', $endDate = 'now', $timezone = null));

            $manager->persist($user);
        }

        $manager->flush();
    }
}