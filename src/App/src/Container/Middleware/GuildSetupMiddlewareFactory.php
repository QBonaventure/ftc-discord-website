<?php

declare(strict_types=1);

namespace App\Container\Middleware;

use Psr\Container\ContainerInterface;
use FTC\Discord\Model\Aggregate\GuildRepository;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Middleware\GuildSetupMiddleware;

class GuildSetupMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : GuildSetupMiddleware
    {
        $guildRepository = $container->get(GuildRepository::class);
        $guildRoleRepository = $container->get(GuildRoleRepository::class);
        $templateRenderer = $container->get(TemplateRendererInterface::class);
        new dklfj;
        return new GuildSetupMiddleware($guildRepository, $guildRoleRepository, $templateRenderer);
    }
}
