<?php

declare(strict_types=1);

namespace App\Container\View\Helper;

use Psr\Container\ContainerInterface;
use App\View\Helper\CdnLocator;

class CdnLocatorFactory
{
    
    public function __invoke(ContainerInterface $container) 
    {
        $config = $container->get('config')->offsetGet('cdn');
        $devMode = $container->get('config')->offsetGet('debug');

        return new CdnLocator($config, $devMode);
    }
    
}
