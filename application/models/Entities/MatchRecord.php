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
     * @var string $title
     */
    private $title;

    /**
     * @var integer $matchType
     */
    private $matchType;

    /**
     * @var integer $leaugeType
     */
    private $leaugeType;

    /**
     * @var integer $teamAId
     */
    private $teamAId;

    /**
     * @var integer $teamBId
     */
    private $teamBId;

    /**
     * @var integer $started
     */
    private $started;

    /**
     * @var integer $ended
     */
    private $ended;

    /**
     * @var integer $canceled
     */
    private $canceled;

    /**
     * @var integer $scoreA
     */
    private $scoreA;

    /**
     * @var integer $scoreB
     */
    private $scoreB;

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
     * @var integer $created
     */
    private $created;

    /**
     * @var integer $lastUpdated
     */
    private $lastUpdated;

    /**
     * @var Entities\MatchRecordPlayerA
     */
    private $teamAStIds;

    /**
     * @var Entities\MatchRecordPlayerAFb
     */
    private $teamAFbIds;

    /**
     * @var Entities\MatchRecordPlayerB
     */
    private $teamBStIds;

    /**
     * @var Entities\MatchRecordPlayerBFb
     */
    private $teamBFbIds;

    public function __construct()
    {
        $this->teamAStIds = new \Doctrine\Common\Collections\ArrayCollection();
    $this->teamAFbIds = new \Doctrine\Common\Collections\ArrayCollection();
    $this->teamBStIds = new \Doctrine\Common\Collections\ArrayCollection();
    $this->teamBFbIds = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set teamAId
     *
     * @param integer $teamAId
     */
    public function setTeamAId($teamAId)
    {
        $this->teamAId = $teamAId;
    }

    /**
     * Get teamAId
     *
     * @return integer $teamAId
     */
    public function getTeamAId()
    {
        return $this->teamAId;
    }

    /**
     * Set teamBId
     *
     * @param integer $teamBId
     */
    public function setTeamBId($teamBId)
    {
        $this->teamBId = $teamBId;
    }

    /**
     * Get teamBId
     *
     * @return integer $teamBId
     */
    public function getTeamBId()
    {
        return $this->teamBId;
    }

    /**
     * Set started
     *
     * @param integer $started
     */
    public function setStarted($started)
    {
        $this->started = $started;
    }

    /**
     * Get started
     *
     * @return integer $started
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * Set ended
     *
     * @param integer $ended
     */
    public function setEnded($ended)
    {
        $this->ended = $ended;
    }

    /**
     * Get ended
     *
     * @return integer $ended
     */
    public function getEnded()
    {
        return $this->ended;
    }

    /**
     * Set canceled
     *
     * @param integer $canceled
     */
    public function setCanceled($canceled)
    {
        $this->canceled = $canceled;
    }

    /**
     * Get canceled
     *
     * @return integer $canceled
     */
    public function getCanceled()
    {
        return $this->canceled;
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
     * @param integer $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return integer $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set lastUpdated
     *
     * @param integer $lastUpdated
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
    }

    /**
     * Get lastUpdated
     *
     * @return integer $lastUpdated
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * Add teamAStIds
     *
     * @param Entities\MatchRecordPlayerA $teamAStIds
     */
    public function addTeamAStIds(\Entities\MatchRecordPlayerA $teamAStIds)
    {
        $this->teamAStIds[] = $teamAStIds;
    }

    /**
     * Get teamAStIds
     *
     * @return Doctrine\Common\Collections\Collection $teamAStIds
     */
    public function getTeamAStIds()
    {
        return $this->teamAStIds;
    }

    /**
     * Add teamAFbIds
     *
     * @param Entities\MatchRecordPlayerAFb $teamAFbIds
     */
    public function addTeamAFbIds(\Entities\MatchRecordPlayerAFb $teamAFbIds)
    {
        $this->teamAFbIds[] = $teamAFbIds;
    }

    /**
     * Get teamAFbIds
     *
     * @return Doctrine\Common\Collections\Collection $teamAFbIds
     */
    public function getTeamAFbIds()
    {
        return $this->teamAFbIds;
    }

    /**
     * Add teamBStIds
     *
     * @param Entities\MatchRecordPlayerB $teamBStIds
     */
    public function addTeamBStIds(\Entities\MatchRecordPlayerB $teamBStIds)
    {
        $this->teamBStIds[] = $teamBStIds;
    }

    /**
     * Get teamBStIds
     *
     * @return Doctrine\Common\Collections\Collection $teamBStIds
     */
    public function getTeamBStIds()
    {
        return $this->teamBStIds;
    }

    /**
     * Add teamBFbIds
     *
     * @param Entities\MatchRecordPlayerBFb $teamBFbIds
     */
    public function addTeamBFbIds(\Entities\MatchRecordPlayerBFb $teamBFbIds)
    {
        $this->teamBFbIds[] = $teamBFbIds;
    }

    /**
     * Get teamBFbIds
     *
     * @return Doctrine\Common\Collections\Collection $teamBFbIds
     */
    public function getTeamBFbIds()
    {
        return $this->teamBFbIds;
    }
    
    /**
     * @prePersist
     */
    public function prePersist() {
        $now = new \DateTime();
        $timestamp = $now->getTimestamp();
        if ($this->created == null) {
            $this->created = $timestamp;
        }
        $this->lastUpdated = $timestamp;
    }

    /**
     * @preUpdate
     */
    public function preUpdate() {
        $now = new \DateTime();
        $timestamp = $now->getTimestamp();
        $this->lastUpdated = $timestamp;
    }

    public function toArray() {
        $array = get_object_vars($this);
        
        $teamAStIds = array();
        $teamAFbIds = array();
        $teamBStIds = array();
        $teamBFbIds = array();
        
        $stIds = $this->getTeamAStIds();
        foreach($stIds as $stId) {
            array_push($teamAStIds, $stId->getStadioomId());
        }
        $fbIds = $this->getTeamAFbIds();
        foreach($fbIds as $fbId) {
            array_push($teamAFbIds, $fbId->getFbId());
        }
        $stIds = $this->getTeamBStIds();
        foreach($stIds as $stId) {
            array_push($teamBStIds, $stId->getStadioomId());
        }
        $fbIds = $this->getTeamBFbIds();
        foreach($fbIds as $fbId) {
            array_push($teamBFbIds, $fbId->getFbId());
        }

        $array['teamAStIds'] = $teamAStIds;
        $array['teamAFbIds'] = $teamAFbIds;
        $array['teamBStIds'] = $teamBStIds;
        $array['teamBFbIds'] = $teamBFbIds;
        
        return $array;
    }
    
}