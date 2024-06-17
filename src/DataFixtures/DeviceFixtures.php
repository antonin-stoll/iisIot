<?php


namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Device;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DeviceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $device = new Device();
        $device->setLabel('Teplomer');
        $device->setDescription('Popis teploměru...');
        $device->setOwner($this->getReference('admin'));
        $manager->persist($device);

        $device2 = new Device();
        $device2->setLabel('Svetlo');
        $device2->setUserAlias('Chandelier');
        $device2->setOwner($this->getReference('admin'));
        $manager->persist($device2);

        $manager->flush();
        $this->addReference('device', $device);
        $this->addReference('device2', $device2);
    }
}
