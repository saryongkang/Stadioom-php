<?php

namespace Entities;

/**
 * Entities\InviteeFb
 */
class InviteeFb
{
    /**
     * @var string $fbId
     */
    private $fbId;

    /**
     * @var integer $invitorId
     */
    private $invitorId;

    /**
     * @var datetime $invitedDate
     */
    private $invitedDate;

    /**
     * @var datetime $acceptedDate
     */
    private $acceptedDate;


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
     * Set invitorId
     *
     * @param integer $invitorId
     */
    public function setInvitorId($invitorId)
    {
        $this->invitorId = $invitorId;
    }

    /**
     * Get invitorId
     *
     * @return integer $invitorId
     */
    public function getInvitorId()
    {
        return $this->invitorId;
    }

    /**
     * Set invitedDate
     *
     * @param datetime $invitedDate
     */
    public function setInvitedDate($invitedDate)
    {
        $this->invitedDate = $invitedDate;
    }

    /**
     * Get invitedDate
     *
     * @return datetime $invitedDate
     */
    public function getInvitedDate()
    {
        return $this->invitedDate;
    }

    /**
     * Set acceptedDate
     *
     * @param datetime $acceptedDate
     */
    public function setAcceptedDate($acceptedDate)
    {
        $this->acceptedDate = $acceptedDate;
    }

    /**
     * Get acceptedDate
     *
     * @return datetime $acceptedDate
     */
    public function getAcceptedDate()
    {
        return $this->acceptedDate;
    }
}