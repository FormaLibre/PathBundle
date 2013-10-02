<?php

namespace Innova\PathBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Step2ResourceNode
 *
 * @ORM\Table("innova_step2resourceNode")
 * @ORM\Entity
 */
class Step2ResourceNode
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="Innova\PathBundle\Entity\Step")
    */
    private $step;

    /**
     * @var boolean
     *
     * @ORM\Column(name="propagated", type="boolean")
     */
    private $propagated;

    /**
     * @ORM\ManyToOne(targetEntity="Claroline\CoreBundle\Entity\Resource\ResourceNode")
    */
    private $resourceNode;

    /**
     * @var boolean
     *
     * @ORM\Column(name="excluded", type="boolean")
     */
    private $excluded;

    /**
     * @var integer
     *
     * @ORM\Column(name="resourceOrder", type="integer", nullable=true)
     */
    private $resourceOrder;

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
     * Set step
     *
     * @param  \Innova\PathBundle\Entity\Step $step
     * @return Step2ResourceNode
     */
    public function setStep(\Innova\PathBundle\Entity\Step $step = null)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get step
     *
     * @return \Innova\PathBundle\Entity\Step
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Set resourceNode
     *
     * @param  \Claroline\CoreBundle\Entity\Resource\ResourceNode $resourceNode
     * @return Step2ResourceNode
     */
    public function setResourceNode(\Claroline\CoreBundle\Entity\Resource\ResourceNode $resourceNode = null)
    {
        $this->resourceNode = $resourceNode;

        return $this;
    }

    /**
     * Get resourceNode
     *
     * @return \Claroline\CoreBundle\Entity\Resource\ResourceNode
     */
    public function getResourceNode()
    {
        return $this->resourceNode;
    }

    /**
     * Set resourceOrder
     *
     * @param  integer           $resourceOrder
     * @return Step2ResourceNode
     */
    public function setResourceOrder($resourceOrder)
    {
        $this->resourceOrder = $resourceOrder;

        return $this;
    }

    /**
     * Get resourceOrder
     *
     * @return integer
     */
    public function getResourceOrder()
    {
        return $this->resourceOrder;
    }

    /**
     * Set propagated
     *
     * @param  boolean           $propagated
     * @return Step2ResourceNode
     */
    public function setPropagated($propagated)
    {
        $this->propagated = $propagated;

        return $this;
    }

    /**
     * Get propagated
     *
     * @return boolean
     */
    public function getPropagated()
    {
        return $this->propagated;
    }

    /**
     * Set excluded
     *
     * @param  boolean           $excluded
     * @return Step2ResourceNode
     */
    public function setExcluded($excluded)
    {
        $this->excluded = $excluded;

        return $this;
    }

    /**
     * Get excluded
     *
     * @return boolean
     */
    public function getExcluded()
    {
        return $this->excluded;
    }
}
