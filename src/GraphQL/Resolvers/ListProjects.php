<?php

namespace App\GraphQL\Resolvers;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;

class ListProjects implements ResolverInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke($value, Argument $args, \ArrayObject $context, ResolveInfo $info)
    {
        $paginator = new Paginator(function($offset, $limit) {
            return $this->entityManager
                ->getRepository(Project::class)
                ->findBy([], null, $limit, $offset);
        });

        $total = $this->entityManager->createQueryBuilder()
            ->select('count(p.id)')
            ->from('App:Project', 'p')
            ->getQuery()
            ->getSingleScalarResult();

        return $paginator->auto($args, $total);
    }
}
