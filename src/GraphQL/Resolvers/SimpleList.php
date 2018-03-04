<?php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;

abstract class SimpleList implements ResolverInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    abstract protected function getEntityClassName(): string;

    protected function getQueryBuilder(array $filters): QueryBuilder
    {
        $query = $this->entityManager->createQueryBuilder()
            ->select('e')
            ->from($this->getEntityClassName(), 'e');

        foreach ($filters as $key => $value) {
            $query->andWhere("e.$key = :$key")
                ->setParameter($key, $value);
        }

        return $query;
    }

    public function __invoke($value, Argument $args, \ArrayObject $context, ResolveInfo $info)
    {
        $filters = $args['filter'] ?? [];

        $paginator = new Paginator(function($offset, $limit) use ($filters) {
            return $this->getQueryBuilder($filters)
                ->getQuery()
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getResult();
        });

        $total = $this->getQueryBuilder($filters)
            ->select('count(e.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $paginator->auto($args, $total);
    }
}
