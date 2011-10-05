<?php

namespace Entities;

/**
 * Entities\User
 */
class User
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $fbId
     */
    private $fbId;

    /**
     * @var boolean $fbLinked
     */
    private $fbLinked;

    /**
     * @var boolean $fbAuthorized
     */
    private $fbAuthorized;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $gender
     */
    private $gender;

    /**
     * @var datetime $dob
     */
    private $dob;

    /**
     * @var boolean $verified
     */
    private $verified;

    /**
     * @var integer $created
     */
    private $created;

    /**
     * @var integer $lastUpdated
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
     * Set fbId
     *
     * @param string $fbId
     */
    public function setFbId($fbId)
    {
        $this->fbId = $fbId;
    }

    /**
     * Get fbId
     *
     * @return string $fbId
     */
    public function getFbId()
    {
        return $this->fbId;
    }

    /**
     * Set fbLinked
     *
     * @param boolean $fbLinked
     */
    public function setFbLinked($fbLinked)
    {
        $this->fbLinked = $fbLinked;
    }

    /**
     * Get fbLinked
     *
     * @return boolean $fbLinked
     */
    public function getFbLinked()
    {
        return $this->fbLinked;
    }

    /**
     * Set fbAuthorized
     *
     * @param boolean $fbAuthorized
     */
    public function setFbAuthorized($fbAuthorized)
    {
        $this->fbAuthorized = $fbAuthorized;
    }

    /**
     * Get fbAuthorized
     *
     * @return boolean $fbAuthorized
     */
    public function getFbAuthorized()
    {
        return $this->fbAuthorized;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
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
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set gender
     *
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get gender
     *
     * @return string $gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set dob
     *
     * @param datetime $dob
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    }

    /**
     * Get dob
     *
     * @return datetime $dob
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set verified
     *
     * @param boolean $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    }

    /**
     * Get verified
     *
     * @return boolean $verified
     */
    public function getVerified()
    {
        return $this->verified;
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
        return get_object_vars($this);
    }

}