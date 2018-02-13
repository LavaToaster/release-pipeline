<?php

namespace App\Command;

use App\Entity\Project;
use App\Services\CreateCodeBuildProject;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProjectAddCommand extends Command
{
    protected static $defaultName = 'project:add';

    /**
     * @var CreateCodeBuildProject
     */
    private $createCodeBuildProject;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        CreateCodeBuildProject $createCodeBuildProject,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();

        $this->createCodeBuildProject = $createCodeBuildProject;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Adds a Project')
            ->addOption('container', null, InputOption::VALUE_NONE)
            ->addOption('parent', null, InputOption::VALUE_OPTIONAL)
            ->addArgument('name', InputOption::VALUE_REQUIRED)
        ;
    }

    protected function interact(
        InputInterface $input,
        OutputInterface $output
    ) {
        $io = new SymfonyStyle($input, $output);

        if (! $input->getArgument('name')) {
            $input->setArgument('name', $io->ask('Project Name'));
        }

        if (! $input->getOption('parent')) {
            $input->setOption('parent', $io->ask('Parent Name'));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $project = new Project(
            Uuid::uuid4(),
            $input->getArgument('name')
        );

        if (! $input->getOption('container')) {
            // TODO: Looks fugly. Make it less so.
            $project->setCodeBuildProjectName(
                ($this->createCodeBuildProject)($project->getName())
            );
        }

        if ($parentName = $input->getOption('parent')) {
            $parent = $this->entityManager->getRepository(Project::class)->findOneBy(['name' => $parentName]);
            $project->setParent($parent);
        }

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $io->success('Project Added');
    }
}
