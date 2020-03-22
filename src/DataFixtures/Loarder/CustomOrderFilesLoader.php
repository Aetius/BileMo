<?php


namespace App\DataFixtures\Loader;


use Doctrine\ORM\EntityManagerInterface;
use Fidry\AliceDataFixtures\LoaderInterface;
use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Nelmio\Alice\IsAServiceTrait;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class CustomOrderFilesLoader implements LoaderInterface
{
    use IsAServiceTrait;

    private $decoratedLoader;

    public function __construct(LoaderInterface $decoratedLoader)
    {
        $this->decoratedLoader = $decoratedLoader;
    }

    /**
     * @inheritDoc
     */
    public function load(array $fixturesFiles, array $parameters = [], array $objects = [], PurgeMode $purgeMode = null): array
    {
        foreach ($fixturesFiles as $key=>$value)
        {
            if ($value == "fixtures\BrandsFixturesDatas.yaml")
            {
                
            }
        }
        // We get the objects from the decorated loader
        $objects = $this->decoratedLoader->load($fixturesFiles, $parameters, $objects, $purgeMode);
       // dd($objects);
        // TODO: re-order the objects we want them to be persisted

        return $objects;
    }
}