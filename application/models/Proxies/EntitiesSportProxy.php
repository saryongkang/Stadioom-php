<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesSportProxy extends \Entities\Sport implements \Doctrine\ORM\Proxy\Proxy
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
    
    
    public function toArray()
    {
        $this->__load();
        return parent::toArray();
    }

    public function getId()
    {
        $this->__load();
        return parent::getId();
    }

    public function setName($name)
    {
        $this->__load();
        return parent::setName($name);
    }

    public function getName()
    {
        $this->__load();
        return parent::getName();
    }

    public function setDescription($description)
    {
        $this->__load();
        return parent::setDescription($description);
    }

    public function getDescription()
    {
        $this->__load();
        return parent::getDescription();
    }

    public function setFirstRevision($firstRevision)
    {
        $this->__load();
        return parent::setFirstRevision($firstRevision);
    }

    public function getFirstRevision()
    {
        $this->__load();
        return parent::getFirstRevision();
    }

    public function setLatestRevision($latestRevision)
    {
        $this->__load();
        return parent::setLatestRevision($latestRevision);
    }

    public function getLatestRevision()
    {
        $this->__load();
        return parent::getLatestRevision();
    }

    public function setUpdateFlag($updateFlag)
    {
        $this->__load();
        return parent::setUpdateFlag($updateFlag);
    }

    public function getUpdateFlag()
    {
        $this->__load();
        return parent::getUpdateFlag();
    }

    public function setPriority($priority)
    {
        $this->__load();
        return parent::setPriority($priority);
    }

    public function getPriority()
    {
        $this->__load();
        return parent::getPriority();
    }

    public function setStringId($stringId)
    {
        $this->__load();
        return parent::setStringId($stringId);
    }

    public function getStringId()
    {
        $this->__load();
        return parent::getStringId();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'stringId', 'name', 'description', 'priority', 'firstRevision', 'latestRevision', 'updateFlag');
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