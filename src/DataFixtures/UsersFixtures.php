<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class UsersFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $users = $this->userFactory();
        foreach ($users->getObjects() as $user){
            $manager->persist($user);
        }
        $manager->flush();

    }

    private function userFactory()
    {
        $loader = new NativeLoader();
        return $loader->loadFile(__DIR__.'/Datas/UsersFixturesDatas.yaml');
    }
}
