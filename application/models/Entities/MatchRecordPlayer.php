<?php

namespace Entities;

/**
 * Entities\MatchRecordPlayer
 */
class MatchRecordPlayer
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $matchId
     */
    private $matchId;

    /**
     * @var integer $involvedTeam
     */
    private $involvedTeam;

    /**
     * @var integer $stadioomId
     */
    private $stadioomId;

    /**
     * @var string $fbId
     */
    private $fbId;

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
     * Set matchId
     *
     * @param integer $matchId
     */
    public function setMatchId($matchId)
    {
        $this->matchId = $matchId;
    }

    /**
     * Get matchId
     *
     * @return integer $matchId
     */
    public function getMatchId()
    {
        return $this->matchId;
    }

    /**
     * Set involvedTeam
     *
     * @param integer $involvedTeam
     */
    public function setInvolvedTeam($involvedTeam)
    {
        $this->involvedTeam = $involvedTeam;
    }

    /**
     * Get involvedTeam
     *
     * @return integer $involvedTeam
     */
    public function getInvolvedTeam()
    {
        return $this->involvedTeam;
    }

    /**
     * Set stadioomId
     *
     * @param integer $stadioomId
     */
    public function setStadioomId($stadioomId)
    {
        $this->stadioomId = $stadioomId;
    }

    /**
     * Get stadioomId
     *
     * @return integer $stadioomId
     */
    public function getStadioomId()
    {
        return $this->stadioomId;
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
}