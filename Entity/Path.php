<?php

namespace Innova\PathBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Claroline\CoreBundle\Entity\Resource\AbstractResource;
/**
 * Path
 *
 * @ORM\Table(name="innova_path")
 * @ORM\Entity(repositoryClass="Innova\PathBundle\Repository\PathRepository")
 */
class Path extends AbstractResource
{

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="text")
     */
    private $path;

    /**
    * @ORM\OneToMany(targetEntity="Step", mappedBy="path", cascade={"remove"})
    */
    protected $steps;

    /**
    * @ORM\OneToMany(targetEntity="User2Path", mappedBy="path")
    */
    protected $users;

    /**
     * Set path
     *
     * @param  string $path
     * @return Path
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->steps = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add steps
     *
     * @param  \Innova\PathBundle\Entity\Step $steps
     * @return Path
     */
    public function addStep(\Innova\PathBundle\Entity\Step $steps)
    {
        $this->steps[] = $steps;

        return $this;
    }

    /**
     * Remove steps
     *
     * @param \Innova\PathBundle\Entity\Step $steps
     */
    public function removeStep(\Innova\PathBundle\Entity\Step $steps)
    {
        $this->steps->removeElement($steps);
    }

    /**
     * Get steps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSteps()
    {
        return $this->steps;
    }

}
