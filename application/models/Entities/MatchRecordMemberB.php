<?php

namespace Entities;

/**
 * Entities\MatchRecordMemberB
 */
class MatchRecordMemberB
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $userId
     */
    private $userId;

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
     * Set userId
     *
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get userId
     *
     * @return integer $userId
     */
    public function getUserId()
    {
        return $this->userId;
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
}