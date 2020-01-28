<?php

namespace Smart\AuthenticationBundle\Entity\User;

use Smart\AuthenticationBundle\Validator\Constraints as SmartAssert;

trait PasswordSafeableTrait
{
    /**
     * @var string
     *
     * @SmartAssert\IsPasswordSafe
     */
    private $plainPassword;
}
