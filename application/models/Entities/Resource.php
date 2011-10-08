<?php

namespace Entities;

/**
 * Entities\Resource
 */
class Resource
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $numId
     */
    private $numId;

    /**
     * @var string $strId
     */
    private $strId;

    /**
     * @var string $msg
     */
    private $msg;

    /**
     * @var string $msgiOS
     */
    private $msgiOS;

    /**
     * @var string $msgJS
     */
    private $msgJS;

    /**
     * @var string $lang
     */
    private $lang;

    /**
     * @var datetime $lastUpdated
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
     * Set numId
     *
     * @param integer $numId
     */
    public function setNumId($numId)
    {
        $this->numId = $numId;
    }

    /**
     * Get numId
     *
     * @return integer $numId
     */
    public function getNumId()
    {
        return $this->numId;
    }

    /**
     * Set strId
     *
     * @param string $strId
     */
    public function setStrId($strId)
    {
        $this->strId = $strId;
    }

    /**
     * Get strId
     *
     * @return string $strId
     */
    public function getStrId()
    {
        return $this->strId;
    }

    /**
     * Set msg
     *
     * @param string $msg
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
    }

    /**
     * Get msg
     *
     * @return string $msg
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * Set msgiOS
     *
     * @param string $msgiOS
     */
    public function setMsgiOS($msgiOS)
    {
        $this->msgiOS = $msgiOS;
    }

    /**
     * Get msgiOS
     *
     * @return string $msgiOS
     */
    public function getMsgiOS()
    {
        return $this->msgiOS;
    }

    /**
     * Set msgJS
     *
     * @param string $msgJS
     */
    public function setMsgJS($msgJS)
    {
        $this->msgJS = $msgJS;
    }

    /**
     * Get msgJS
     *
     * @return string $msgJS
     */
    public function getMsgJS()
    {
        return $this->msgJS;
    }

    /**
     * Set lang
     *
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * Get lang
     *
     * @return string $lang
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set lastUpdated
     *
     * @param datetime $lastUpdated
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
    }

    /**
     * Get lastUpdated
     *
     * @return datetime $lastUpdated
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }
    /**
     * @prePersist
     */
    public function prePersist() {
        $this->lastUpdated = new \DateTime();
    }

    /**
     * @preUpdate
     */
    public function preUpdate() {
        $this->lastUpdated = new \DateTime();
    }
}