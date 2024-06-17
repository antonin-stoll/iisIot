<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\System;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SystemFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $system = new System();
        $system->setName("adminuv system");
        $system->setDescription("Toto je adminuv system!");
        $system->setOwner($this->getReference('admin'));
        $system->addDevice($this->getReference('device2'));
        $manager->persist($system);

        $manager->flush();

    }
}
