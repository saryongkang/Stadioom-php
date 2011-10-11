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
     * @var integer $leagueType
     */
    private $leagueType;

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
     * @var Entities\MatchRecordMemberA
     */
    private $memberIdsA;

    /**
     * @var Entities\MatchRecordMemberB
     */
    private $memberIdsB;

    public function __construct()
    {
        $this->memberIdsA = new \Doctrine\Common\Collections\ArrayCollection();
    $this->memberIdsB = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set leagueType
     *
     * @param integer $leagueType
     */
    public function setLeagueType($leagueType)
    {
        $this->leagueType = $leagueType;
    }

    /**
     * Get leagueType
     *
     * @return integer $leagueType
     */
    public function getLeagueType()
    {
        return $this->leagueType;
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

        $memberIdsA = array();
        $memberIdsB = array();

        $membersA = $this->getMembersA();
        foreach ($membersA as $member) {
            array_push($memberIdsA, $member->getId());
        }
        $membersB = $this->getMembersB();
        foreach ($membersB as $member) {
            array_push($memberIdsB, $member->getId());
        }

        $array['memberIdsA'] = $memberIdsA;
        $array['memberIdsB'] = $memberIdsB;
        
        unset($array['membersA']);
        unset($array['membersB']);

        return $array;
    }
    /**
     * @var Entities\User
     */
    private $membersA;

    /**
     * @var Entities\User
     */
    private $membersB;


    /**
     * Add membersA
     *
     * @param Entities\User $membersA
     */
    public function addMembersA(\Entities\User $membersA)
    {
        $this->membersA[] = $membersA;
    }

    /**
     * Get membersA
     *
     * @return Doctrine\Common\Collections\Collection $membersA
     */
    public function getMembersA()
    {
        return $this->membersA;
    }

    /**
     * Add membersB
     *
     * @param Entities\User $membersB
     */
    public function addMembersB(\Entities\User $membersB)
    {
        $this->membersB[] = $membersB;
    }

    /**
     * Get membersB
     *
     * @return Doctrine\Common\Collections\Collection $membersB
     */
    public function getMembersB()
    {
        return $this->membersB;
    }
}