<?php
// app/AppKernel.php

// ...
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
        );

        // ...
    }

    // ...
    public function registerContainerConfiguration(\Symfony\Component\Config\Loader\LoaderInterface $loader)
    {
        // TODO: Implement registerContainerConfiguration() method.
    }
}
