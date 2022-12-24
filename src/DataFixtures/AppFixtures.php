<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Invoice;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * l'hasher de mots de passe
     * 
     * @var UserPasswordHasherInterface
     */
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        
        for ($u=0; $u < 10 ; $u++) { 
            $user = new User();

            $chrono = 1;

            $hast = $this->encoder->hashPassword($user, "password");
            
            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setPassword($hast);
            $manager->persist($user);
            
            for ($c = 0; $c < mt_rand(5,20); $c++) {
                $customer = new Customer();
                $customer->setFirstName($faker->firstName);
                $customer->setLastName($faker->lastName);
                $customer->setCompany($faker->company);
                $customer->setEmail($faker->email);
                $customer->setUser($user);
            $manager->persist($customer);
    
            for ($i=0; $i < mt_rand(3, 10) ; $i++) { 
                $invoice = new Invoice();
                $invoice->setAmount($faker->randomFloat(2, 250, 5000))
                ->setSentAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-3 months')));
                $invoice->setStatus($faker->randomElement(['SENT','PAID', 'CANCELLED']))
                        ->setCustomer($customer)
                        ->setChrono($chrono);
                    
                        $chrono++;
            $manager->persist($invoice);
    
    
            }    
            }

        }

        

        $manager->flush();
    }
    }

