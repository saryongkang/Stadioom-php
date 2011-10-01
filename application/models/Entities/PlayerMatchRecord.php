<?php

namespace Entities;

/**
 * Entities\PlayerMatchRecord
 */
class PlayerMatchRecord
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $userId
     */
    private $userId;

    /**
     * @var integer $sportId
     */
    private $sportId;

    /**
     * @var integer $matchId
     */
    private $matchId;

    /**
     * @var integer $brandId
     */
    private $brandId;

    /**
     * @var integer $score
     */
    private $score;

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
     * Set userId
     *
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get userId
     *
     * @return integer $userId
     */
    public function getUserId()
    {
        return $this->userId;
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
     * Set matchId
     *
     * @param integer $matchId
     */
    public function setMatchId($matchId)
    {
        $this->matchId = $matchId;
    }

    /**
     * Get matchId
     *
     * @return integer $matchId
     */
    public function getMatchId()
    {
        return $this->matchId;
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
     * Set score
     *
     * @param integer $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * Get score
     *
     * @return integer $score
     */
    public function getScore()
    {
        return $this->score;
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
}