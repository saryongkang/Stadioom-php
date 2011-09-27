<?php

namespace Entities;

/**
 * Entities\InviteeFb
 */
class InviteeFb
{
    /**
     * @var string $inviteeFbId
     */
    private $inviteeFbId;

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
     * Set inviteeFbId
     *
     * @param string $inviteeFbId
     */
    public function setInviteeFbId($inviteeFbId)
    {
        $this->inviteeFbId = $inviteeFbId;
    }

    /**
     * Get inviteeFbId
     *
     * @return string $inviteeFbId
     */
    public function getInviteeFbId()
    {
        return $this->inviteeFbId;
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