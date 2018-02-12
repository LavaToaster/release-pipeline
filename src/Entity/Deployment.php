<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity()
 * @ORM\Table(name="deployments")
 */
class Deployment
{
    /*
     * State a deployment can be in:
     *
     * SCHEDULED - Initial log to system with a run date in the future
     * PENDING - Initial log with a run date of now, or SCHEDULED date is now
     * RUNNING - Has been sent to CodeDeploy
     * SUCCESSFUL(Message) - Deployment Successful
     * FAILED(Reason) - Deployment Failed
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Environment")
     *
     * @var Environment
     */
    protected $environment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Release")
     *
     * @var Environment
     */
    protected $release;

    /**
     * @ORM\Column(type="json")
     *
     * @var array
     */
    protected $state;
}
