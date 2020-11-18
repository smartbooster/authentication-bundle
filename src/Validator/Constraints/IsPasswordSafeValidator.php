<?php

namespace Smart\AuthenticationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * https://www.ssi.gouv.fr/uploads/IMG/pdf/NP_MDP_NoteTech.pdf.
 */
class IsPasswordSafeValidator extends ConstraintValidator
{
    const MINIMAL_STRING_LENGTH = 10;
    const COINTANS_LOWER_CHARACTER_REGEX = '/[a-z]+/';
    const COINTANS_UPPER_CHARACTER_REGEX = '/[A-Z]+/';
    const COINTANS_NUMBER_REGEX = '/[0-9]+/';

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IsPasswordSafe) {
            throw new UnexpectedTypeException($constraint, IsPasswordSafe::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (\strlen($value) < self::MINIMAL_STRING_LENGTH) {
            $this->context->buildViolation($constraint->lengthMessage)->addViolation();
        }

        if (!preg_match(self::COINTANS_LOWER_CHARACTER_REGEX, $value, $matches)) {
            $this->context->buildViolation($constraint->missingLowerCharacterMessage)->addViolation();
        }

        if (!preg_match(self::COINTANS_UPPER_CHARACTER_REGEX, $value, $matches)) {
            $this->context->buildViolation($constraint->missingUpperCharacterMessage)->addViolation();
        }

        if (!preg_match(self::COINTANS_NUMBER_REGEX, $value, $matches)) {
            $this->context->buildViolation($constraint->missingNumberMessage)->addViolation();
        }
    }
}
