<?php

namespace Smart\AuthenticationBundle\Tests\Validator\Constraints;

use Smart\AuthenticationBundle\Validator\Constraints\IsPasswordSafe;
use Smart\AuthenticationBundle\Validator\Constraints\IsPasswordSafeValidator;

/**
 * vendor/bin/phpunit tests/Validator/Constraints/IsPasswordSafeValidatorTest.php.
 */
class IsPasswordSafeValidatorTest extends AbstractValidatorTest
{
    /**
     * {@inheritdoc}
     */
    protected function getValidatorInstance()
    {
        return new IsPasswordSafeValidator();
    }

    /**
     * Test not valid password.
     *
     * @param string $value
     * @param string $expectedMessage
     * @dataProvider failPasswordProvider
     */
    public function testValidationFail($value, $expectedMessage): void
    {
        $constraint = new IsPasswordSafe();
        $validator = $this->initValidator($expectedMessage);

        $validator->validate($value, $constraint);
    }

    /**
     * @return array
     */
    public function failPasswordProvider()
    {
        return [
            ['too_SH0RT', 'is_password_safe.length_error'],
            ['0MISSING_LOWER', 'is_password_safe.missing_lower_character_error'],
            ['0missing_upper', 'is_password_safe.missing_upper_character_error'],
            ['missing_NUMBER', 'is_password_safe.missing_number_error'],
        ];
    }

    /**
     * Test valid password.
     *
     * @param string $value
     * @dataProvider validPasswordProvider
     */
    public function testValidationOk($value): void
    {
        $constraint = new IsPasswordSafe();
        $validator = $this->initValidator();

        $validator->validate($value, $constraint);
    }

    /**
     * @return array
     */
    public function validPasswordProvider()
    {
        return [
            ['Aa12345678'],
            ['MySuperC00lPassword'],
        ];
    }
}
