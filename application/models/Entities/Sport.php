<?php

namespace Entities;

/**
 * Entities\Sport
 */
class Sport
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $description
     */
    private $description;

    /**
     * @var integer $weight
     */
    private $weight;

    /**
     * @var integer $firstRevision
     */
    private $firstRevision;

    /**
     * @var integer $latestRevision
     */
    private $latestRevision;

    /**
     * @var integer $updateFlag
     */
    private $updateFlag;


    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * Get weight
     *
     * @return integer $weight
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set firstRevision
     *
     * @param integer $firstRevision
     */
    public function setFirstRevision($firstRevision)
    {
        $this->firstRevision = $firstRevision;
    }

    /**
     * Get firstRevision
     *
     * @return integer $firstRevision
     */
    public function getFirstRevision()
    {
        return $this->firstRevision;
    }

    /**
     * Set latestRevision
     *
     * @param integer $latestRevision
     */
    public function setLatestRevision($latestRevision)
    {
        $this->latestRevision = $latestRevision;
    }

    /**
     * Get latestRevision
     *
     * @return integer $latestRevision
     */
    public function getLatestRevision()
    {
        return $this->latestRevision;
    }

    /**
     * Set updateFlag
     *
     * @param integer $updateFlag
     */
    public function setUpdateFlag($updateFlag)
    {
        $this->updateFlag = $updateFlag;
    }

    /**
     * Get updateFlag
     *
     * @return integer $updateFlag
     */
    public function getUpdateFlag()
    {
        return $this->updateFlag;
    }
}