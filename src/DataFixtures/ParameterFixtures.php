<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Parameter;
use App\Entity\System;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ParameterFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $parameter = new Parameter();
        $parameter->setName('teplota[˚C]');
        $parameter->setValue('35');
        $parameter->setMinValue(-273.15);
        $parameter->setDevice($this->getReference('device'));

        $manager->persist($parameter);

        $manager->flush();

        $this->addReference('parameter', $parameter);
    }
}
