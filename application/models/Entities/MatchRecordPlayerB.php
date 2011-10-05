<?php

namespace Entities;

/**
 * Entities\MatchRecordPlayerB
 */
class MatchRecordPlayerB
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $stadioomId
     */
    private $stadioomId;

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