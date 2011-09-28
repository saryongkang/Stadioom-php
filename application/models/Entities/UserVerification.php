<?php

namespace Entities;

/**
 * Entities\UserVerification
 */
class UserVerification
{
    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $code
     */
    private $code;

    /**
     * @var datetime $issuedDate
     */
    private $issuedDate;


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
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string $code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set issuedDate
     *
     * @param datetime $issuedDate
     */
    public function setIssuedDate($issuedDate)
    {
        $this->issuedDate = $issuedDate;
    }

    /**
     * Get issuedDate
     *
     * @return datetime $issuedDate
     */
    public function getIssuedDate()
    {
        return $this->issuedDate;
    }
}