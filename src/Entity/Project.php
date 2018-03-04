<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="projects")
 */
class Project
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="children")
     *
     * @var null|Project
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project", mappedBy="parent")
     *
     * @var Project[]|ArrayCollection
     */
    protected $children;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Environment", mappedBy="project")
     *
     * @var Environment[]|ArrayCollection
     */
    protected $environments;

    /**
     * @ORM\Column(type="json")
     *
     * @var array
     */
    protected $config;

    /**
     * Project constructor.
     * @param string $id
     * @param string $name
     * @param Project|null $parent
     * @param Environment[]|null $environments
     */
    public function __construct(
        string $id,
        string $name,
        ?Project $parent = null,
        array $environments = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->parent = $parent;
        $this->config = [];
        $this->environments = new ArrayCollection($environments);
        $this->children = new ArrayCollection();
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
    public function getParent(): ?Project
    {
        return $this->parent;
    }

    /**
     * @param Project|null $parent
     */
    public function setParent(?Project $parent): void
    {
        $this->parent = $parent;

        if ($parent) {
            $parent->addChild($this);
        }
    }

    /**
     * @return Project[]
     */
    public function getChildren(): array
    {
        return $this->children->toArray();
    }

    public function addChild(Project $project)
    {
        if ($this->children->contains($project)) {
            return;
        }

        $this->children->add($project);
        $project->setParent($this);
    }

    /**
     * @return Environment[]
     */
    public function getEnvironments(): array
    {
        return $this->environments->toArray();
    }

    /**
     * @param Environment $environment
     */
    public function addEnvironment(Environment $environment): void
    {
        if ($this->environments->contains($environment)) {
            return;
        }

        $this->environments->add($environment);
        $environment->setProject($this);
    }

    public function removeEnvironment(Environment $environment): void
    {
        if (! $this->environments->contains($environment)) {
            return;
        }

        $this->environments->removeElement($environment);
        $environment->setProject(null);
    }

    public function getCodeBuildProjectName(): ?string
    {
        return $this->config['codeBuildName'] ?? null;
    }

    public function setCodeBuildProjectName(string $projectName): void
    {
        $this->config['codeBuildName'] = $projectName;
    }
}
