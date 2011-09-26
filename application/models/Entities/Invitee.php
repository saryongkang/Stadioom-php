<?php

namespace Entities;

/**
 * Entities\Invitee
 */
class Invitee
{
    /**
     * @var string $email
     */
    private $email;

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
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
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