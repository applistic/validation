<?php

namespace Applistic\Validation;

/**
 * Implements the validator interface.
 *
 * @author Frederic Filosa <filosa@applistic.com>
 * @copyright (c) 2014, applistic.com
 */
abstract class AbstractValidator implements ValidatorInterface
{
// ===== CONSTANTS =============================================================
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================

    /**
     * The next validator in the chain.
     *
     * @var \Applistic\Validation\ValidatorInterface
     */
    protected $nextValidator;

    /**
     * All the values.
     *
     * @var array
     */
    protected $values;

// ===== ACCESSORS =============================================================

    /**
     * Sets the values.
     *
     * @param array $values
     */
    public function setValues(array &$values)
    {
        $this->values =& $values;

        if (!is_null($this->nextValidator)) {
            $this->nextValidator->setValues($values);
        }
    }

    /**
     * Return the values.
     *
     * @return array
     */
    public function values()
    {
        return $this->values;
    }

// ===== CONSTRUCTOR ===========================================================
// ===== PUBLIC METHODS ========================================================

    /**
     * Returns true if the validator can perform the validation corresponding
     * to $ruleName.
     *
     * @param  string $ruleName The rule name.
     * @return boolean
     */
    public function hasRule($ruleName)
    {
        if (!is_string($ruleName)) {
            throw new \InvalidArgumentException("\$ruleName must be a string.");
        }

        $methodName = $this->validationMethod($ruleName);
        return method_exists($this, $methodName);
    }

    /**
     * Validates a value using a rule name with optional arguments.
     *
     * @param  mixed  $value     The value to validate.
     * @param  string $ruleName  The rule name.
     * @param  mixed  $arguments Optional arguments.
     * @return boolean
     */
    public function validate($value, $ruleName, $arguments)
    {
        if ($this->hasRule($ruleName)) {

            $methodName = $this->validationMethod($ruleName);
            return $this->$methodName($value, $arguments);

        } elseif (!is_null($this->nextValidator)) {

            return $this->nextValidator->validate($value, $ruleName, $arguments);

        } else {

            $message = "The rule {$ruleName} doesn't exist.";
            throw new \InvalidArgumentException($message);

        }
    }

    /**
     * Sets the next validator.
     *
     * @param ApplisticValidationValidatorInterface $validator [description]
     * @return void
     */
    public function setNextValidator(\Applistic\Validation\ValidatorInterface $validator)
    {
        if ($validator === $this) {
            $this->nextValidator = null;
        } else {
            $this->nextValidator = $validator;
        }
    }

    /**
     * Returns the next validator.
     *
     * @return \Applistic\Validation\ValidatorInterface
     */
    public function nextValidator()
    {
        return $this->nextValidator;
    }

    /**
     * Sets the last validator of the chain.
     *
     * @param ApplisticValidationValidatorInterface $validator
     */
    public function setLastValidator(\Applistic\Validation\ValidatorInterface $validator)
    {
        if (is_null($this->nextValidator)) {
            $this->nextValidator = $validator;
        } else {
            $this->nextValidator->setLastValidator($validator);
        }
    }

    /**
     * Returns the last validator.
     *
     * @return \Applistic\Validation\ValidatorInterface
     */
    public function lastValidator()
    {
        if (!is_null($this->nextValidator)) {
            return $this->nextValidator->lastValidator();
        } else {
            return $this;
        }
    }

// ===== PROTECTED METHODS =====================================================

    /**
     * Returns the method used to validate the $ruleName.
     *
     * @param  string $ruleName The rule name.
     * @return string
     */
    protected function validationMethod($ruleName)
    {
        return "is".ucfirst($ruleName);
    }

// ===== PRIVATE METHODS =======================================================
}