<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity()
 * @ORM\Table(name="environments")
 */
class Environment
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="NONE")
     *
     * @var string
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project")
     *
     * @var Project|null
     */
    protected $project;

    /**
     * Environment constructor.
     * @param string $id
     * @param string $name
     * @param Project $project
     */
    public function __construct(
        string $id,
        string $name,
        Project $project
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->setProject($project);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Project|null
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * @param Project|null $project
     */
    public function setProject(?Project $project): void
    {
        // Prevents circular reference when removing the environment
        $oldProject = $this->project;
        $this->project = $project;

        if ($oldProject) {
            $oldProject->removeEnvironment($this);
        }

        if ($project) {
            $project->addEnvironment($this);
        }
    }
}
