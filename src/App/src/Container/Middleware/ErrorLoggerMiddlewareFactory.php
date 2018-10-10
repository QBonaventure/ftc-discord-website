<?php

declare(strict_types=1);

namespace App\Container\Middleware;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Middleware\ErrorLoggerMiddleware;

class ErrorLoggerMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ErrorLoggerMiddleware
    {
        $isDebugMode = $container->get('config')['debug'];
        $template = $container->get(TemplateRendererInterface::class);
        
        return new ErrorLoggerMiddleware($template, $isDebugMode);
    }
}
