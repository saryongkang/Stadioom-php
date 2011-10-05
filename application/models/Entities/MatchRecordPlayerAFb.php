<?php

namespace Entities;

/**
 * Entities\MatchRecordPlayerAFb
 */
class MatchRecordPlayerAFb
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
     * @var Entities\MatchRecord
     */
    private $match;


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
     * Set match
     *
     * @param Entities\MatchRecord $match
     */
    public function setMatch(\Entities\MatchRecord $match)
    {
        $this->match = $match;
    }

    /**
     * Get match
     *
     * @return Entities\MatchRecord $match
     */
    public function getMatch()
    {
        return $this->match;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}