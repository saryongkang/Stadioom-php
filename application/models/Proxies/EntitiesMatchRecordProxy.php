<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesMatchRecordProxy extends \Entities\MatchRecord implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }
    
    
    public function getId()
    {
        $this->__load();
        return parent::getId();
    }

    public function setOwnerId($ownerId)
    {
        $this->__load();
        return parent::setOwnerId($ownerId);
    }

    public function getOwnerId()
    {
        $this->__load();
        return parent::getOwnerId();
    }

    public function setSportId($sportId)
    {
        $this->__load();
        return parent::setSportId($sportId);
    }

    public function getSportId()
    {
        $this->__load();
        return parent::getSportId();
    }

    public function setBrandId($brandId)
    {
        $this->__load();
        return parent::setBrandId($brandId);
    }

    public function getBrandId()
    {
        $this->__load();
        return parent::getBrandId();
    }

    public function setTitle($title)
    {
        $this->__load();
        return parent::setTitle($title);
    }

    public function getTitle()
    {
        $this->__load();
        return parent::getTitle();
    }

    public function setMatchType($matchType)
    {
        $this->__load();
        return parent::setMatchType($matchType);
    }

    public function getMatchType()
    {
        $this->__load();
        return parent::getMatchType();
    }

    public function setLeaugeType($leaugeType)
    {
        $this->__load();
        return parent::setLeaugeType($leaugeType);
    }

    public function getLeaugeType()
    {
        $this->__load();
        return parent::getLeaugeType();
    }

    public function setTeamAId($teamAId)
    {
        $this->__load();
        return parent::setTeamAId($teamAId);
    }

    public function getTeamAId()
    {
        $this->__load();
        return parent::getTeamAId();
    }

    public function setTeamBId($teamBId)
    {
        $this->__load();
        return parent::setTeamBId($teamBId);
    }

    public function getTeamBId()
    {
        $this->__load();
        return parent::getTeamBId();
    }

    public function setStarted($started)
    {
        $this->__load();
        return parent::setStarted($started);
    }

    public function getStarted()
    {
        $this->__load();
        return parent::getStarted();
    }

    public function setEnded($ended)
    {
        $this->__load();
        return parent::setEnded($ended);
    }

    public function getEnded()
    {
        $this->__load();
        return parent::getEnded();
    }

    public function setCanceled($canceled)
    {
        $this->__load();
        return parent::setCanceled($canceled);
    }

    public function getCanceled()
    {
        $this->__load();
        return parent::getCanceled();
    }

    public function setScoreA($scoreA)
    {
        $this->__load();
        return parent::setScoreA($scoreA);
    }

    public function getScoreA()
    {
        $this->__load();
        return parent::getScoreA();
    }

    public function setScoreB($scoreB)
    {
        $this->__load();
        return parent::setScoreB($scoreB);
    }

    public function getScoreB()
    {
        $this->__load();
        return parent::getScoreB();
    }

    public function setLocation($location)
    {
        $this->__load();
        return parent::setLocation($location);
    }

    public function getLocation()
    {
        $this->__load();
        return parent::getLocation();
    }

    public function setLatitude($latitude)
    {
        $this->__load();
        return parent::setLatitude($latitude);
    }

    public function getLatitude()
    {
        $this->__load();
        return parent::getLatitude();
    }

    public function setLongitude($longitude)
    {
        $this->__load();
        return parent::setLongitude($longitude);
    }

    public function getLongitude()
    {
        $this->__load();
        return parent::getLongitude();
    }

    public function setCreated($created)
    {
        $this->__load();
        return parent::setCreated($created);
    }

    public function getCreated()
    {
        $this->__load();
        return parent::getCreated();
    }

    public function setLastUpdated($lastUpdated)
    {
        $this->__load();
        return parent::setLastUpdated($lastUpdated);
    }

    public function getLastUpdated()
    {
        $this->__load();
        return parent::getLastUpdated();
    }

    public function addMemberIdsA(\Entities\MatchRecordMemberA $memberIdsA)
    {
        $this->__load();
        return parent::addMemberIdsA($memberIdsA);
    }

    public function getMemberIdsA()
    {
        $this->__load();
        return parent::getMemberIdsA();
    }

    public function addMemberIdsB(\Entities\MatchRecordMemberB $memberIdsB)
    {
        $this->__load();
        return parent::addMemberIdsB($memberIdsB);
    }

    public function getMemberIdsB()
    {
        $this->__load();
        return parent::getMemberIdsB();
    }

    public function prePersist()
    {
        $this->__load();
        return parent::prePersist();
    }

    public function preUpdate()
    {
        $this->__load();
        return parent::preUpdate();
    }

    public function toArray()
    {
        $this->__load();
        return parent::toArray();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'ownerId', 'sportId', 'brandId', 'title', 'matchType', 'leaugeType', 'teamAId', 'teamBId', 'started', 'ended', 'canceled', 'scoreA', 'scoreB', 'location', 'latitude', 'longitude', 'created', 'lastUpdated', 'memberIdsA', 'memberIdsB');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}