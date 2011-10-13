<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesUserProxy extends \Entities\User implements \Doctrine\ORM\Proxy\Proxy
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

    public function setFbLinked($fbLinked)
    {
        $this->__load();
        return parent::setFbLinked($fbLinked);
    }

    public function getFbLinked()
    {
        $this->__load();
        return parent::getFbLinked();
    }

    public function setFbAuthorized($fbAuthorized)
    {
        $this->__load();
        return parent::setFbAuthorized($fbAuthorized);
    }

    public function getFbAuthorized()
    {
        $this->__load();
        return parent::getFbAuthorized();
    }

    public function setPassword($password)
    {
        $this->__load();
        return parent::setPassword($password);
    }

    public function getPassword()
    {
        $this->__load();
        return parent::getPassword();
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

    public function setEmail($email)
    {
        $this->__load();
        return parent::setEmail($email);
    }

    public function getEmail()
    {
        $this->__load();
        return parent::getEmail();
    }

    public function setGender($gender)
    {
        $this->__load();
        return parent::setGender($gender);
    }

    public function getGender()
    {
        $this->__load();
        return parent::getGender();
    }

    public function setDob($dob)
    {
        $this->__load();
        return parent::setDob($dob);
    }

    public function getDob()
    {
        $this->__load();
        return parent::getDob();
    }

    public function setVerified($verified)
    {
        $this->__load();
        return parent::setVerified($verified);
    }

    public function getVerified()
    {
        $this->__load();
        return parent::getVerified();
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


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'fbId', 'fbLinked', 'fbAuthorized', 'password', 'name', 'email', 'gender', 'dob', 'verified', 'created', 'lastUpdated');
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