<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class BrandsFixtures extends Fixture
{
    const NB_BRAND = 10;

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $brandsObject = $this->brandFactory();
        foreach ($brandsObject->getObjects() as $key=>$brand){
            $manager->persist($brand);
            $this->addReference($key, $brand);
        }
        $manager->flush();

    }

    private function brandFactory()
    {
        $loader = new NativeLoader();
        return $loader->loadFile(__DIR__.'/Datas/BrandsFixturesDatas.yaml');
    }


}
