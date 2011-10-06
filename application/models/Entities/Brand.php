<?php

namespace Entities;

/**
 * Entities\Brand
 */
class Brand {

    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $stringId
     */
    private $stringId;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $description
     */
    private $description;

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

    public function toArray() {
        return get_object_vars($this);
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set firstRevision
     *
     * @param integer $firstRevision
     */
    public function setFirstRevision($firstRevision) {
        $this->firstRevision = $firstRevision;
    }

    /**
     * Get firstRevision
     *
     * @return integer $firstRevision
     */
    public function getFirstRevision() {
        return $this->firstRevision;
    }

    /**
     * Set latestRevision
     *
     * @param integer $latestRevision
     */
    public function setLatestRevision($latestRevision) {
        $this->latestRevision = $latestRevision;
    }

    /**
     * Get latestRevision
     *
     * @return integer $latestRevision
     */
    public function getLatestRevision() {
        return $this->latestRevision;
    }

    /**
     * Set updateFlag
     *
     * @param integer $updateFlag
     */
    public function setUpdateFlag($updateFlag) {
        $this->updateFlag = $updateFlag;
    }

    /**
     * Get updateFlag
     *
     * @return integer $updateFlag
     */
    public function getUpdateFlag() {
        return $this->updateFlag;
    }

    /**
     * @var integer $priority
     */
    private $priority;

    /**
     * Set priority
     *
     * @param integer $priority
     */
    public function setPriority($priority) {
        $this->priority = $priority;
    }

    /**
     * Get priority
     *
     * @return integer $priority
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * Set stringId
     *
     * @param string $stringId
     */
    public function setStringId($stringId) {
        $this->stringId = $stringId;
    }

    /**
     * Get stringId
     *
     * @return string $stringId
     */
    public function getStringId() {
        return $this->stringId;
    }

}