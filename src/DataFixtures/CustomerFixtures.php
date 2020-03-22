<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class CustomerFixtures extends Fixture
{

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $customerObject = $this->customerFactory();
        foreach ($customerObject->getObjects() as $key=>$customer){
            $manager->persist($customer);
            $this->addReference($key, $customer);
        }
        $manager->flush();

    }

    private function customerFactory()
    {
        $loader = new NativeLoader();
        return $loader->loadFile(__DIR__.'/Datas/CustomersFixturesDatas.yaml');
    }


}