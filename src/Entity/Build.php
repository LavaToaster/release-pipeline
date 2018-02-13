<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;

/**
 * @ApiResource()
 * @ORM\Entity()
 * @ORM\Table(name="builds")
 */
class Build
{
    /*
     * State a build can be in:
     *
     * PENDING - Initial log to system
     * RUNNING - Has been sent to CodeBuild
     * SUCCESSFUL - Build Successful
     * FAILED - Build Failed
     */

    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="NONE")
     *
     * @var string
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project")
     *
     * @var Project
     */
    protected $project;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $number;

    /**
     * @ORM\Column(type="json")
     *
     * @var array
     */
    protected $state;

    /**
     * Build constructor.
     * @param string $id
     * @param Project $project
     * @param int $number
     */
    public function __construct(
        string $id,
        Project $project,
        int $number
    ) {
        $this->id = $id;
        $this->project = $project;
        $this->number = $number;
        $this->state = [];
    }

    /**
     * @return array
     */
    public function getState(): array
    {
        return $this->state;
    }

    /**
     * @param array $state
     */
    public function setState(array $state): void
    {
        $this->state = $state;
    }
}
