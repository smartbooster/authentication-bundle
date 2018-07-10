<?php

namespace Smart\AuthenticationBundle\Security;

/**
 * Add methods for handling last login
 */
interface LastLoginInterface
{
    /**
     * @return null|DateTime
     */
    public function getLastLogin();

    /**
     * @param  null|DateTime $plainPassword
     */
    public function setLastLogin($lastLogin = null);
}
