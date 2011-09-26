<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesInviteeFbProxy extends \Entities\InviteeFb implements \Doctrine\ORM\Proxy\Proxy
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
    
    
    public function setInvitorId($invitorId)
    {
        $this->__load();
        return parent::setInvitorId($invitorId);
    }

    public function getInvitorId()
    {
        $this->__load();
        return parent::getInvitorId();
    }

    public function setFbId($fbId)
    {
        $this->__load();
        return parent::setFbId($fbId);
    }

    public function getFbId()
    {
        $this->__load();
        return parent::getFbId();
    }

    public function setInvitedDate($invitedDate)
    {
        $this->__load();
        return parent::setInvitedDate($invitedDate);
    }

    public function getInvitedDate()
    {
        $this->__load();
        return parent::getInvitedDate();
    }

    public function setAcceptedDate($acceptedDate)
    {
        $this->__load();
        return parent::setAcceptedDate($acceptedDate);
    }

    public function getAcceptedDate()
    {
        $this->__load();
        return parent::getAcceptedDate();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'invitorId', 'fbId', 'invitedDate', 'acceptedDate');
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