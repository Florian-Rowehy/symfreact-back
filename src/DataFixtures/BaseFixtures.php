<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixtures extends Fixture
{
    private ObjectManager $manager;
    protected Generator $faker;
    private array $referencesIndex;

    public function __construct()
    {
        $this->faker = Factory::create('en_US');
        $this->referencesIndex = [];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
    }

    protected function createMany(string $className, int $nbRep, callable $factory, ?int &$ref = null): void
    {
        for ($i = 0; $i < $nbRep; $i++) {
            $entity = new $className();
            $factory($entity, $i, $ref);

            $this->manager->persist($entity);

            if ($ref !== null) {
                $this->addReference($className . '_' . $ref, $entity);
                $ref++;
            } else {
                $this->addReference($className . "_" . $i, $entity);
            }
        }
    }
}
