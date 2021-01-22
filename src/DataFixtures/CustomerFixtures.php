<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        parent::load($manager);
        $regex = '/User_\d+/';
        $refCustomer = 0;
        foreach (array_keys($this->referenceRepository->getReferences()) as $ref) {
            if (!preg_match($regex, $ref)) {
                continue;
            }
            $this->createMany(
                Customer::class,
                rand(3, 10),
                function ($customer) use ($ref) {
                    $customer
                        ->setFirstName($this->faker->firstName)
                        ->setLastName($this->faker->lastName)
                        ->setEmail($this->faker->email)
                        ->setCompany($this->faker->company)
                        ->setUser($this->getReference($ref))
                    ;
                },
                $refCustomer
            );
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
