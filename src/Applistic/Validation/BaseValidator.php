<?php

namespace Applistic\Validation;

/**
 * Validator for basic validations such as string, integers, lengths, etc.
 *
 * @author Frederic Filosa <filosa@applistic.com>
 * @copyright (c) 2014, applistic.com
 */
class BaseValidator extends AbstractValidator
{
// ===== CONSTANTS =============================================================

    const NUMERIC_PATTERN = "|^[\-]{0,1}[0-9]+[\.]{0,1}[0-9]+$|";
    const INTEGER_PATTERN = "|^[\-]{0,1}[0-9]+$|";

    const MSG_CANNOT_APPLY_TO = "The `%s` rule cannot be applied to: %s";

// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================
// ===== ACCESSORS =============================================================
// ===== CONSTRUCTOR ===========================================================
// ===== PUBLIC METHODS ========================================================

    /**
     * Checks if the value is a string.
     *
     * @param  mixed  $value
     * @return boolean
     */
    public function isString($value)
    {
        return is_string($value);
    }

    /**
     * Checks if the value represents an integer (strings are accepted).
     *
     * @param  mixed  $value
     * @return boolean
     */
    public function isInteger($value)
    {
        if (is_int($value)) {
            return true;
        } elseif (is_string($value) && preg_match(self::INTEGER_PATTERN, $value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if the value is an integer.
     *
     * @param  mixed  $value
     * @return boolean
     */
    public function isRealInteger($value)
    {
        return is_int($value);
    }

    /**
     * Checks if the value represents a numeric.
     *
     * @param  mixed  $value
     * @return boolean
     */
    public function isNumeric($value)
    {
        if (is_numeric($value)) {
            return true;
        } elseif (is_string($value)) {
            return preg_match(self::NUMERIC_PATTERN, $value);
        } else {
            return false;
        }
    }

    /**
     * Checks if the value is numeric.
     *
     * @param  mixed  $value
     * @return boolean
     */
    public function isRealNumeric($value)
    {
        return is_numeric($value);
    }

    /**
     * Checks if the value is an array.
     *
     * @param  mixed  $value
     * @return boolean
     */
    public function isArray($value)
    {
        return is_array($value);
    }

    /**
     * Checks if the value equals or is superior to the $argument.
     *
     * The validation depends on the value type:
     * - for a numeric value, it has to match the minimum $argument.
     * - for a string, its length must be superior or equals the minimum $argument.
     * - for an array, its count must be superior or equals the minimum $argument.
     *
     * @param  mixed      $value
     * @param  $argument  $argument  The minimum requirement (must be castable to double).
     * @return boolean
     */
    public function isMin($value, &$argument)
    {
        $argument = (double)$argument;

        if (is_string($value)) {

            return (mb_strlen($value) >= $argument);

        } elseif (is_numeric($value)) {

            return ($value >= $argument);

        } elseif (is_array($value)) {

            return (count($value) >= $argument);

        } else {

            $message = sprintf(self::MSG_CANNOT_APPLY_TO, "min", var_export($value, true));
            throw new \InvalidArgumentException($message);

        }
    }

    /**
     * Checks if the value equals or is inferior to the $argument.
     *
     * The validation depends on the value type:
     * - for a numeric value, it has to match the maximum $argument.
     * - for a string, its length must be inferior or equals the minimum $argument.
     * - for an array, its count must be inferior or equals the minimum $argument.
     *
     * @param  mixed      $value
     * @param  $argument  $argument  The maximum requirement (must be castable to double).
     * @return boolean
     */
    public function isMax($value, &$argument)
    {
        $argument = (double)$argument;

        if (is_string($value)) {

            return (mb_strlen($value) <= $argument);

        } elseif (is_numeric($value)) {

            return ($value <= $argument);

        } elseif (is_array($value)) {

            return (count($value) <= $argument);

        } else {

            $message = sprintf(self::MSG_CANNOT_APPLY_TO, "max", var_export($value, true));
            throw new \InvalidArgumentException($message);

        }
    }

    /**
     * Checks if the $value is an alpha-numeric string.
     *
     * @param  mixed  $value
     * @return boolean
     */
    public function isAlphanum($value)
    {
        if (!is_string($value)) {
            return false;
        } else {
            return ctype_alnum($value);
        }
    }

    /**
     * Checks if the $value is an alphabet only string.
     *
     * @param  mixed  $value
     * @return boolean
     */
    public function isAlpha($value)
    {
        if (!is_string($value)) {
            return false;
        } else {
            return ctype_alpha($value);
        }
    }

// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}