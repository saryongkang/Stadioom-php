<?php

namespace Entities;

/**
 * Entities\UserFb
 */
class UserFb
{
    /**
     * @var string $fbId
     */
    private $fbId;

    /**
     * @var string $fbAccessToken
     */
    private $fbAccessToken;

    /**
     * @var integer $fbExpires
     */
    private $fbExpires;

    /**
     * @var string $gender
     */
    private $gender;

    /**
     * @var string $locale
     */
    private $locale;

    /**
     * @var integer $timezone
     */
    private $timezone;

    /**
     * @var string $birthday
     */
    private $birthday;

    /**
     * @var string $hometown
     */
    private $hometown;

    /**
     * @var string $location
     */
    private $location;

    /**
     * @var string $favorite_athletes
     */
    private $favorite_athletes;

    /**
     * @var string $favorite_teams
     */
    private $favorite_teams;


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
     * Set fbAccessToken
     *
     * @param string $fbAccessToken
     */
    public function setFbAccessToken($fbAccessToken)
    {
        $this->fbAccessToken = $fbAccessToken;
    }

    /**
     * Get fbAccessToken
     *
     * @return string $fbAccessToken
     */
    public function getFbAccessToken()
    {
        return $this->fbAccessToken;
    }

    /**
     * Set fbExpires
     *
     * @param integer $fbExpires
     */
    public function setFbExpires($fbExpires)
    {
        $this->fbExpires = $fbExpires;
    }

    /**
     * Get fbExpires
     *
     * @return integer $fbExpires
     */
    public function getFbExpires()
    {
        return $this->fbExpires;
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
     * Set locale
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Get locale
     *
     * @return string $locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set timezone
     *
     * @param integer $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * Get timezone
     *
     * @return integer $timezone
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set birthday
     *
     * @param string $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * Get birthday
     *
     * @return string $birthday
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set hometown
     *
     * @param string $hometown
     */
    public function setHometown($hometown)
    {
        $this->hometown = $hometown;
    }

    /**
     * Get hometown
     *
     * @return string $hometown
     */
    public function getHometown()
    {
        return $this->hometown;
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
     * Set favorite_athletes
     *
     * @param string $favoriteAthletes
     */
    public function setFavoriteAthletes($favoriteAthletes)
    {
        $this->favorite_athletes = $favoriteAthletes;
    }

    /**
     * Get favorite_athletes
     *
     * @return string $favoriteAthletes
     */
    public function getFavoriteAthletes()
    {
        return $this->favorite_athletes;
    }

    /**
     * Set favorite_teams
     *
     * @param string $favoriteTeams
     */
    public function setFavoriteTeams($favoriteTeams)
    {
        $this->favorite_teams = $favoriteTeams;
    }

    /**
     * Get favorite_teams
     *
     * @return string $favoriteTeams
     */
    public function getFavoriteTeams()
    {
        return $this->favorite_teams;
    }
    /**
     * @var string $name
     */
    private $name;


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
     * @var Entities\LikesFb
     */
    private $likes;

    public function __construct()
    {
        $this->likes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->interests = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add likes
     *
     * @param Entities\LikesFb $likes
     */
    public function addLikes(\Entities\LikesFb $likes)
    {
        $this->likes[] = $likes;
    }

    /**
     * Get likes
     *
     * @return Doctrine\Common\Collections\Collection $likes
     */
    public function getLikes()
    {
        return $this->likes;
    }
    /**
     * @var Entities\ActivitiesFb
     */
    private $activities;


    /**
     * Add activities
     *
     * @param Entities\ActivitiesFb $activities
     */
    public function addActivities(\Entities\ActivitiesFb $activities)
    {
        $this->activities[] = $activities;
    }

    /**
     * Get activities
     *
     * @return Doctrine\Common\Collections\Collection $activities
     */
    public function getActivities()
    {
        return $this->activities;
    }
    /**
     * @var Entities\InterestsFb
     */
    private $interests;


    /**
     * Add interests
     *
     * @param Entities\InterestsFb $interests
     */
    public function addInterests(\Entities\InterestsFb $interests)
    {
        $this->interests[] = $interests;
    }

    /**
     * Get interests
     *
     * @return Doctrine\Common\Collections\Collection $interests
     */
    public function getInterests()
    {
        return $this->interests;
    }
}