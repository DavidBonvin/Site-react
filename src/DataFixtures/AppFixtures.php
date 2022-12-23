<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Invoice;
use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;



class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($c = 0; $c < 30; $c++) {
            $customer = new Customer();
            $customer->setFirstName($faker->firstName);
            $customer->setLastName($faker->lastName);
            $customer->setCompany($faker->company);
            $customer->setEmail($faker->email);
            $manager->persist($customer);

        for ($i=0; $i < mt_rand(3, 10) ; $i++) { 
            $invoice = new Invoice();
            $invoice->setAmount($faker->randomFloat(2, 250, 5000))
            ->setSentAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-3 months')));
            $invoice->setStatus($faker->randomElement(['SENT','PAID', 'CANCELLED']))
                    ->setCustomer($customer);
            $manager->persist($invoice);


        }    
        }

        $manager->flush();
    }
    }

