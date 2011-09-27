<?php

namespace Entities;

/**
 * Entities\Invitee
 */
class Invitee
{
    /**
     * @var string $inviteeEmail
     */
    private $inviteeEmail;

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
     * Set inviteeEmail
     *
     * @param string $inviteeEmail
     */
    public function setInviteeEmail($inviteeEmail)
    {
        $this->inviteeEmail = $inviteeEmail;
    }

    /**
     * Get inviteeEmail
     *
     * @return string $inviteeEmail
     */
    public function getInviteeEmail()
    {
        return $this->inviteeEmail;
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