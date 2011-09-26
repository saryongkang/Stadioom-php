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
}