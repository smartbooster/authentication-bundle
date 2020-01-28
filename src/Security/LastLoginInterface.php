<?php

namespace Smart\AuthenticationBundle\Security;

/**
 * Add methods for handling last login
 */
interface LastLoginInterface
{
    /**
     * @return null|\DateTime
     */
    public function getLastLogin();

    /**
     * @param  null|\DateTime $lastLogin
     *
     * @return void
     */
    public function setLastLogin($lastLogin = null);
}
