<?php

namespace App\DataFixtures;

use App\Entity\KPI;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class KPIFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $KPI = new KPI();
        $KPI->setName('Hoří');
        $KPI->setExpression('>100');
        $KPI->setDevice($this->getReference('device'));
        $KPI->setParameter($this->getReference('parameter'));
        $manager->persist($KPI);

        $manager->flush();

        $this->addReference('kpi', $KPI);
    }

    public function getDependencies()
    {
        return [
            ParameterFixtures::class
        ];
    }
}
