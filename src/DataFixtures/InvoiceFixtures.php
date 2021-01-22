<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Invoice;
use Doctrine\Persistence\ObjectManager;
use DateTime;

class InvoiceFixtures extends BaseFixtures
{
    public function load(ObjectManager $manager): void
    {
        parent::load($manager);
        $regex = '/Customer_\d+/';
        $refInvoice = 0;
        foreach (array_keys($this->referenceRepository->getReferences()) as $ref) {
            if (!preg_match($regex, $ref)) {
                continue;
            }
            $this->createMany(
                Invoice::class,
                rand(0, 20),
                function ($invoice, $i, $refInvoice) use ($ref) {
                    $status = ['SENT', 'PAID', 'CANCELLED'];
                    $invoice
                        ->setAmount($this->faker->randomFloat(2, 100, 99999))
                        ->setSentAt((new DateTime())->modify('-'.rand(0,365).'days'))
                        ->setStatus($this->faker->randomElement($status))
                        ->setReference($refInvoice)
                        ->setCustomer($this->getReference($ref))
                    ;
                },
                $refInvoice
            );
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            Customer::class,
        );
    }
}
