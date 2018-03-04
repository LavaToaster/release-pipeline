<?php

namespace App\GraphQL;

use Overblog\GraphQLBundle\Resolver\ResolverMap as BaseResolverMap;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ResolverMap extends BaseResolverMap implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array|callable[]
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    protected function map()
    {
        return [
            'Query' => [
                'projects' => $this->container->get(Resolvers\Projects::class),
                'builds' => $this->container->get(Resolvers\Builds::class),
            ],
        ];
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
