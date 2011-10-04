<?php

namespace Entities;

/**
 * Entities\MatchRecordTeam
 */
class MatchRecordTeam
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $teamId
     */
    private $teamId;


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
     * Set teamId
     *
     * @param integer $teamId
     */
    public function setTeamId($teamId)
    {
        $this->teamId = $teamId;
    }

    /**
     * Get teamId
     *
     * @return integer $teamId
     */
    public function getTeamId()
    {
        return $this->teamId;
    }
}