<?php

namespace App\Tests\Entity;

use App\Entity\Environment;
use App\Entity\Project;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class EnvironmentTest extends TestCase
{
    public function testItUpdatesRelationshipProperly()
    {
        // Setup
        $project = new Project(Uuid::uuid4(), 'Test Project');
        $newProject = new Project(Uuid::uuid4(), 'Test Project 2');
        $environment = new Environment(
            Uuid::uuid4(),
            'Test Environment',
            $project
        );

        // Ensure that the constructor has added this environment to the project.
        $this->assertContains($environment, $project->getEnvironments());

        // Replace the project on the environment
        $environment->setProject($newProject);

        // Ensure that the code has removed the environment from the old project
        // and added it to the new project.
        $this->assertNotContains($environment, $project->getEnvironments());
        $this->assertEmpty($project->getEnvironments());
        $this->assertContains($environment, $newProject->getEnvironments());
    }
}
