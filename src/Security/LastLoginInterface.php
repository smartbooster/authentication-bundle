<?php

namespace Smart\AuthenticationBundle\Security;

/**
 * Add methods for handling last login.
 */
interface LastLoginInterface
{
    /**
     * @return \DateTime|null
     */
    public function getLastLogin();

    /**
     * @param \DateTime|null $lastLogin
     *
     * @return void
     */
    public function setLastLogin($lastLogin = null);
}
