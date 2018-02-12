<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity()
 * @ORM\Table(name="releases")
 */
class Release
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
    protected $version;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Build")
     * @ORM\JoinTable(
     *  name="release_build",
     *  joinColumns={
     *      @ORM\JoinColumn(name="release_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="build_id", referencedColumnName="id")
     *  }
     * )
     *
     * @var Build[]
     */
    protected $builds;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Release")
     * @ORM\JoinTable(
     *  name="release_release",
     *  joinColumns={
     *      @ORM\JoinColumn(name="parent_release_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="child_release_id", referencedColumnName="id")
     *  }
     * )
     *
     * @var Release[]
     */
    protected $releases;

    /**
     * @ORM\Column(type="json")
     *
     * @var array
     */
    protected $state;
}
