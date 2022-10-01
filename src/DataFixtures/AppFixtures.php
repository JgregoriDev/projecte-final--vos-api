<?php

namespace App\DataFixtures;

use App\Entity\Genere;
use App\Entity\Plataforma;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\Videojoc;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use WW\Faker\Provider\Picture;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private Generator $faker;
    private ParameterBagInterface $parameterBag;
    public function __construct(UserPasswordHasherInterface $passwordHasher, ParameterBagInterface $parameterBag)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create();
        $this->faker->addProvider(new Picture($this->faker));
        $this->parameterBag = $parameterBag;
    }
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $hash = $this->passwordHasher->hashPassword($user, "admin");
        $user->setEmail("admin")
            ->setPassword($hash)
            ->setRoles(["ROLE_ADMIN", "ROLE_USER"]);
        $manager->persist($user);
        $user = new User();
        $hash = $this->passwordHasher->hashPassword($user, "user");
        $user->setEmail("user")
            ->setPassword($hash)
            ->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        //Generes
        $generes = [];
        $genere = new Genere();
        $genere->setGenere("Aventura");
        $manager->persist($genere);
        array_push($generes, $genere);
        $genere = new Genere();
        $genere->setGenere("Accio");
        $manager->persist($genere);
        array_push($generes, $genere);
        $genere = new Genere();
        $genere->setGenere("Western");
        $manager->persist($genere);
        array_push($generes, $genere);
        $genere = new Genere();
        $genere->setGenere("Estrategia");
        $manager->persist($genere);
        array_push($generes, $genere);
        //Plataformes
        $plataformes = [];
        $plataforma = new Plataforma();
        $plataforma->setPlataforma("PS5");
        $manager->persist($plataforma);
        array_push($plataformes, $plataforma);
        $plataforma = new Plataforma();
        $plataforma->setPlataforma("Xbox Series X");
        $manager->persist($plataforma);
        array_push($plataformes, $plataforma);
        $plataforma = new Plataforma();
        $plataforma->setPlataforma("Nintendo Switch");
        $manager->persist($plataforma);
        array_push($plataformes, $plataforma);
        $plataforma = new Plataforma();
        $plataforma->setPlataforma("Steam");
        $manager->persist($plataforma);
        array_push($plataformes, $plataforma);
        //Crear videojocs
        for ($i = 0; $i < 20; $i++) {
            $videojoc = new Videojoc();
            $videojoc->setTitol($this->faker->text(100))
                ->setCantitat(100)
                ->setResena($this->faker->text(255))
                ->setDataCreacio($this->faker->dateTime())
                ->addGenere($generes[$this->faker->numberBetween(0, 3)])
                ->addPlataforma($plataformes[$this->faker->numberBetween(0, 3)])

                ->setPreu($this->faker->randomFloat(2, 0, 80));
            $manager->persist($videojoc);
        }
        $manager->flush();
    }
}
