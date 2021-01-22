<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends BaseFixtures
{
    public function load(ObjectManager $manager): void
    {
        parent::load($manager);
        $this->createMany(
            Customer::class,
            10,
            function ($customer) {
                $customer
                    ->setFirstName($this->faker->firstName)
                    ->setLastName($this->faker->lastName)
                    ->setEmail($this->faker->email)
                    ->setCompany($this->faker->company)
                ;
            }
        );
        $manager->flush();
    }
}
