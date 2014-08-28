<?php

namespace Okra\OkraBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Okra\OkraBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPassword('Katleen2229!');
        $userAdmin->setEmail('jms@grazulex.be');

        $manager->persist($userAdmin);
        $manager->flush();
    }
}
