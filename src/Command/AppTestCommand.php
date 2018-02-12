<?php

namespace App\Command;

use App\Entity\Build;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Workflow\Registry;

class AppTestCommand extends Command
{
    protected static $defaultName = 'app:test';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Registry
     */
    private $registry;

    public function __construct(EntityManagerInterface $entityManager, Registry $registry)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
        $this->registry = $registry;
    }

    protected function configure()
    {
        $this
            ->setDescription('Test, for science!');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $io = new SymfonyStyle($input, $output);

        $io->title('Test Command');

//        $this->makeBuild();
        $build = $this->entityManager->find(Build::class, '1e2b9c54-f401-4544-9e1a-bf303a39780f');

        dump($this->registry->get($build)->getEnabledTransitions($build));

        $workflow = $this->registry->get($build);
        $workflow->apply($build, 'processing');

        $this->entityManager->persist($build);
        $this->entityManager->flush();
    }

    protected function makeBuild(): void
    {
        $project = $this->entityManager->find(Project::class, '1d525576-4565-41df-9b0e-12fb57335073');

        $build = new Build(
            Uuid::uuid4(),
            $project,
            1
        );

        $this->entityManager->persist($build);
        $this->entityManager->flush();
    }
}
