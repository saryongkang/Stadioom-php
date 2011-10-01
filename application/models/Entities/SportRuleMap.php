<?php

namespace Entities;

/**
 * Entities\SportRuleMap
 */
class SportRuleMap
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $sportId
     */
    private $sportId;

    /**
     * @var integer $ruleId
     */
    private $ruleId;


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
     * Set sportId
     *
     * @param integer $sportId
     */
    public function setSportId($sportId)
    {
        $this->sportId = $sportId;
    }

    /**
     * Get sportId
     *
     * @return integer $sportId
     */
    public function getSportId()
    {
        return $this->sportId;
    }

    /**
     * Set ruleId
     *
     * @param integer $ruleId
     */
    public function setRuleId($ruleId)
    {
        $this->ruleId = $ruleId;
    }

    /**
     * Get ruleId
     *
     * @return integer $ruleId
     */
    public function getRuleId()
    {
        return $this->ruleId;
    }
}