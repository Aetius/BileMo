<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Faker\Provider\AliceProvider;
use Nelmio\Alice\Loader\NativeLoader;

class PhonesFixtures extends Fixture
{

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
            $phonesObject = $this->phoneFactory();
            foreach ($phonesObject->getObjects() as $phone){
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
