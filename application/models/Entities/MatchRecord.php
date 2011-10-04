<?php

namespace Entities;

/**
 * Entities\MatchRecord
 */
class MatchRecord
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $ownerId
     */
    private $ownerId;

    /**
     * @var integer $sportId
     */
    private $sportId;

    /**
     * @var integer $brandId
     */
    private $brandId;

    /**
     * @var integer $matchType
     */
    private $matchType;

    /**
     * @var integer $leaugeType
     */
    private $leaugeType;

    /**
     * @var datetime $started
     */
    private $started;

    /**
     * @var datetime $ended
     */
    private $ended;

    /**
     * @var datetime $canceled
     */
    private $canceled;

    /**
     * @var string $location
     */
    private $location;

    /**
     * @var integer $latitude
     */
    private $latitude;

    /**
     * @var integer $longitude
     */
    private $longitude;

    /**
     * @var datetime $created
     */
    private $created;

    /**
     * @var datetime $lastUpdated
     */
    private $lastUpdated;


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
     * Set ownerId
     *
     * @param integer $ownerId
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
    }

    /**
     * Get ownerId
     *
     * @return integer $ownerId
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * Set sportId
     *
     * @param integer $sportId
     */
    public function setSportId($sportId)
    {
        $this->sportId = $sportId;
    }

    /**
     * Get sportId
     *
     * @return integer $sportId
     */
    public function getSportId()
    {
        return $this->sportId;
    }

    /**
     * Set brandId
     *
     * @param integer $brandId
     */
    public function setBrandId($brandId)
    {
        $this->brandId = $brandId;
    }

    /**
     * Get brandId
     *
     * @return integer $brandId
     */
    public function getBrandId()
    {
        return $this->brandId;
    }

    /**
     * Set matchType
     *
     * @param integer $matchType
     */
    public function setMatchType($matchType)
    {
        $this->matchType = $matchType;
    }

    /**
     * Get matchType
     *
     * @return integer $matchType
     */
    public function getMatchType()
    {
        return $this->matchType;
    }

    /**
     * Set leaugeType
     *
     * @param integer $leaugeType
     */
    public function setLeaugeType($leaugeType)
    {
        $this->leaugeType = $leaugeType;
    }

    /**
     * Get leaugeType
     *
     * @return integer $leaugeType
     */
    public function getLeaugeType()
    {
        return $this->leaugeType;
    }

    /**
     * Set started
     *
     * @param datetime $started
     */
    public function setStarted($started)
    {
        $this->started = $started;
    }

    /**
     * Get started
     *
     * @return datetime $started
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * Set ended
     *
     * @param datetime $ended
     */
    public function setEnded($ended)
    {
        $this->ended = $ended;
    }

    /**
     * Get ended
     *
     * @return datetime $ended
     */
    public function getEnded()
    {
        return $this->ended;
    }

    /**
     * Set canceled
     *
     * @param datetime $canceled
     */
    public function setCanceled($canceled)
    {
        $this->canceled = $canceled;
    }

    /**
     * Get canceled
     *
     * @return datetime $canceled
     */
    public function getCanceled()
    {
        return $this->canceled;
    }

    /**
     * Set location
     *
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Get location
     *
     * @return string $location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set latitude
     *
     * @param integer $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get latitude
     *
     * @return integer $latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param integer $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get longitude
     *
     * @return integer $longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set created
     *
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return datetime $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set lastUpdated
     *
     * @param datetime $lastUpdated
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
    }

    /**
     * Get lastUpdated
     *
     * @return datetime $lastUpdated
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * @prePersist
     */
    public function prePersist() {
        $now = new \DateTime();
        if ($this->created == null) {
            $this->created = $now;
        }
        $this->lastUpdated = $now;
    }

    /**
     * @preUpdate
     */
    public function preUpdate() {
        $this->lastUpdated = new \DateTime();
    }
    /**
     * @var string $title
     */
    private $title;

    /**
     * @var integer $scoreA
     */
    private $scoreA;

    /**
     * @var integer $scoreB
     */
    private $scoreB;


    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set scoreA
     *
     * @param integer $scoreA
     */
    public function setScoreA($scoreA)
    {
        $this->scoreA = $scoreA;
    }

    /**
     * Get scoreA
     *
     * @return integer $scoreA
     */
    public function getScoreA()
    {
        return $this->scoreA;
    }

    /**
     * Set scoreB
     *
     * @param integer $scoreB
     */
    public function setScoreB($scoreB)
    {
        $this->scoreB = $scoreB;
    }

    /**
     * Get scoreB
     *
     * @return integer $scoreB
     */
    public function getScoreB()
    {
        return $this->scoreB;
    }
}