<?php


namespace App\Listener\Versionning;


use App\Versionning\Version;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\Routing\Generator\CompiledUrlGenerator;
use Symfony\Component\Routing\RequestContext;

class VersioningListener
{

    /**
     * @var array version
     */
    private $version;
    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var RequestContext
     */
    private $context;

    public function __construct(Reader $reader, string $version, RequestContext $context)
    {
        $this->reader = $reader;
        $this->version = $version;
        $this->context = $context;
    }


    public function onKernelControllerarguments(ControllerArgumentsEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        if (!is_array($controllers = $event->getController())){
            throw new \Laminas\Code\Exception\BadMethodCallException('This method is not available with your current version.');
        }
        if ($controllers[0] instanceof RedirectController){
            return;
        }

        $annotationMethod = $this->getAnnotationMethod($controllers);
        $versionURI = $event->getRequest()->attributes->get($this->version);

        /** @var Version $annotationMethod */
        $this->handle($annotationMethod, $versionURI);

        $this->generateUrlWithVersion($versionURI);

        return;
    }

    private function getAnnotationMethod(iterable $controllers)
    {
        list($controller, $methodName) = $controllers;

        try{
            $controller = new ReflectionClass($controller);
        } catch (\ReflectionException $e){
            throw new \Laminas\Code\Exception\RuntimeException('Failed to read the annotation');
        }

        $method = $controller->getMethod($methodName);
        return $this->reader->getMethodAnnotation($method, Version::class);
    }

    private function handle(Version $annotationMethod = null , string $versionURI = null)
    {
        if ($annotationMethod === null || $versionURI === null){
            throw new \Laminas\Code\Exception\RuntimeException("Failed to read the version.");
        }

        if (!in_array($versionURI, $annotationMethod->version)){
            throw new \Laminas\Code\Exception\BadMethodCallException('This method is not available with your current version.');
        }

    }

    private function generateUrlWithVersion($versionURI)
    {
        $urlGenerator = new CompiledUrlGenerator([], $this->context);
        $urlGenerator->getContext()->setParameters(["version"=>"$versionURI"]);
    }

}