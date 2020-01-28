<?php

namespace Smart\AuthenticationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsPasswordSafe extends Constraint
{
    /** @var string */
    public $lengthMessage = 'is_password_safe.length_error';
    /** @var string */
    public $missingLowerCharacterMessage = 'is_password_safe.missing_lower_character_error';
    /** @var string */
    public $missingUpperCharacterMessage = 'is_password_safe.missing_upper_character_error';
    /** @var string */
    public $missingNumberMessage = 'is_password_safe.missing_number_error';
}
