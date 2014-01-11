<?php

namespace Applistic\Validation;

/**
 * Interface for validators.
 *
 * @author Frederic Filosa <filosa@applistic.com>
 * @copyright (c) 2014, applistic.com
 */
interface ValidatorInterface
{
// ===== CONSTANTS =============================================================
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================
// ===== ACCESSORS =============================================================
// ===== CONSTRUCTOR ===========================================================
// ===== PUBLIC METHODS ========================================================

    /**
     * Returns true if the validator can perform the validation corresponding
     * to $ruleName.
     *
     * @param  string $ruleName The rule name.
     * @return boolean
     */
    public function hasRule($ruleName);

    /**
     * Validates a value using a rule name with optional arguments.
     *
     * @param  mixed  $value     The value to validate.
     * @param  string $ruleName  The rule name.
     * @param  mixed  $arguments Optional arguments.
     * @return boolean
     */
    public function validate($value, $ruleName, $arguments);

    /**
     * Sets the next validator.
     *
     * @param ApplisticValidationValidatorInterface $validator [description]
     * @return void
     */
    public function setNextValidator(\Applistic\Validation\ValidatorInterface $validator);

    /**
     * Returns the next validator.
     *
     * @return \Applistic\Validation\ValidatorInterface
     */
    public function nextValidator();

    /**
     * Returns the last validator.
     *
     * @return \Applistic\Validation\ValidatorInterface
     */
    public function lastValidator();


// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}