<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $PasswordHasher)
    {
        $this->userPasswordHasher = $PasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $account = new Account();
        $account->setUsername('admin');
        $account->setRoles(['ROLE_ADMIN']);
        $account->setPassword(
            $this->userPasswordHasher->hashPassword(
                $account,
                'adminPass'
            )
        );
        $manager->persist($account);

        $account2 = new Account();
        $account2->setUsername('broker');
        $account2->setRoles(['ROLE_BROKER']);
        $account2->setPassword(
            $this->userPasswordHasher->hashPassword(
                $account2,
                'brokerPass'
            )
        );
        $manager->persist($account2);

        $manager->flush();

        $this->addReference('admin', $account);
    }
}
