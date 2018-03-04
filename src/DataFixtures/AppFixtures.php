<?php

namespace App\DataFixtures;

use App\Entity\Build;
use App\Entity\Environment;
use App\Entity\Project;
use App\Entity\Release;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AppFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User(
            Uuid::uuid4(),
            'test@test.com',
            'test',
            'Test',
            'Test'
        );

        $manager->persist($user);

        $projects = [
            [
                'name' => 'Test Client',
                'projects' => [
                    [
                        'name' => 'Laravel API',
                        'environments' => [
                            'QA',
                            'UAT',
                            'PROD'
                        ]
                    ],
                    [
                        'name' => 'NodeJS Back Office',
                        'environments' => [
                            'QA',
                            'UAT',
                            'PROD'
                        ]
                    ],
                    [
                        'name' => 'NodeJS Front Office',
                        'environments' => [
                            'QA',
                            'UAT',
                            'PROD'
                        ]
                    ]
                ]
            ]
        ];

        foreach ($projects as $projectConfig) {
            $project = new Project(
                Uuid::uuid4(),
                $projectConfig['name']
            );

            foreach ($projectConfig['projects'] as $subProjectConfig) {
                $subProject = new Project(
                    Uuid::uuid4(),
                    $subProjectConfig['name'],
                    $project
                );

                foreach ($subProjectConfig['environments'] as $environmentName) {
                    $environment = new Environment(
                        Uuid::uuid4(),
                        $environmentName,
                        $subProject
                    );

                    $manager->persist($environment);
                }

                for ($i = 0; $i < 100; $i++) {
                    $build = new Build(
                        Uuid::uuid4(),
                        $subProject,
                        $i + 1
                    );

                    $manager->persist($build);
                }

                $manager->persist($subProject);
            }
            $manager->persist($project);
        }

        $manager->flush();
    }
}
