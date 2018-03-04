<?php

namespace App\GraphQL\Mutation;

use App\Entity\Build;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use function dump;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Ramsey\Uuid\Uuid;

class CreateBuild implements MutationInterface
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
        $input = $args['input'] ?? [];

        $this->entityManager->beginTransaction();
        $previousBuildNumber = $this->entityManager
            ->createQueryBuilder()
            ->select('t.number')
            ->from('App:Build', 't')
            ->where('t.project = :project')
            ->setParameter('project', $input['project'])
            ->orderBy('t.number', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->getSingleScalarResult();

        $nextBuildNumber = $previousBuildNumber + 1;

        $build = new Build(Uuid::uuid4(), $this->entityManager->getReference(Project::class, $input['project']), $nextBuildNumber);
        $this->entityManager->persist($build);
        $this->entityManager->flush();
        $this->entityManager->commit();

        return [
            'clientMutationId' => $input['clientMutationId'],
            'build' => $build,
        ];
    }
}
