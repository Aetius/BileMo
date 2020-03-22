<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class PhonesFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
            $phones = $this->phoneFactory();
            foreach ($phones->getObjects() as $phone){
                $phone->setBrand($this->defineBrand());
                $manager->persist($phone);
            }
            $manager->flush();

    }

    private function phoneFactory()
    {
        $loader = new NativeLoader();
        return $loader->loadFile(__DIR__.'/Datas/PhonesFixturesDatas.yaml');
    }

    public function getDependencies()
    {
        return array(
            BrandsFixtures::class
        );
    }

    private function defineBrand()
    {
        $number = rand(1, BrandsFixtures::NB_BRAND);
        $brand = $this->getReference('brand'.$number);
        /** @var Brand $brand */
        return $brand;
    }
}
