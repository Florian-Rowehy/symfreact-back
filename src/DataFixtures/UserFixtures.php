<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends BaseFixtures
{
    public function load(ObjectManager $manager): void
    {
        parent::load($manager);
        $user = new User();
        $user
            ->setEmail('user@gmail.com')
            ->setPassword($this->userPasswordEncoder->encodePassword($user, 'password'))
            ->setFirstName('moi')
            ->setLastName('me')
        ;
        $manager->persist($user);

        $this->createMany(
            User::class,
            10,
            function ($user) {
                $user
                    ->setEmail($this->faker->email)
                    ->setPassword($this->userPasswordEncoder->encodePassword($user, 'password'))
                    ->setFirstName($this->faker->firstName)
                    ->setLastName($this->faker->lastName)
                ;
            }
        );
        $manager->flush();
    }
}
