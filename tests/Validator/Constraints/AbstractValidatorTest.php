<?php

namespace Smart\AuthenticationBundle\Tests\Validator\Constraints;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

abstract class AbstractValidatorTest extends TestCase
{
    /**
     * Return an instance from the validator to test.
     *
     * @return ConstraintValidator
     */
    abstract protected function getValidatorInstance();

    /**
     * @param string|null $expectedMessage
     *
     * @return ConstraintValidator
     */
    protected function initValidator($expectedMessage = null)
    {
        $builder = $this->getMockBuilder(ConstraintViolationBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addViolation'])
            ->getMock();

        $context = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->setMethods(['buildViolation'])
            ->getMock();

        if ($expectedMessage) {
            $context->expects($this->atLeastOnce())
                ->method('buildViolation')
                ->with($this->equalTo($expectedMessage))
                ->willReturn($builder)
            ;
        } else {
            $context->expects($this->never())->method('buildViolation');
        }

        $validator = $this->getValidatorInstance();
        /* @var ExecutionContext $context */
        $validator->initialize($context);

        return $validator;
    }
}
