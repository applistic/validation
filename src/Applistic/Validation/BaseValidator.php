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
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================

    /**
     * Checks if the value is a string.
     *
     * @param  mixed  $value
     * @return boolean
     */
    public static function isString($value)
    {
        return is_string($value);
    }

    /**
     * Checks if the value represents an integer (strings are accepted).
     *
     * @param  mixed  $value
     * @return boolean
     */
    public static function isInteger($value)
    {
        if (is_int($value)) {
            return true;
        } elseif (is_string($value) && preg_match('|^[0-9\-]+$|', $value)) {
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
    public static function isStrictInteger($value)
    {
        return is_int($value);
    }

    /**
     * Checks if the value represents a numeric.
     *
     * @param  mixed  $value
     * @return boolean
     */
    public static function isNumeric($value)
    {
        if (is_numeric($value)) {
            return true;
        } elseif (is_string($value)) {
            return preg_match('|^[\-]{0,1}[0-9]+[\.]{0,1}[0-9]+$|', $value);
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
    public static function isStrictNumeric($value)
    {
        return is_numeric($value);
    }

    /**
     * Checks if the value is an array.
     *
     * @param  mixed  $value
     * @return boolean
     */
    public static function isArray($value)
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
    public static function isMin($value, $argument)
    {
        if (is_numeric($value)) {

            return ($value >= (double)$argument);

        } elseif (is_string($value)) {

            return (mb_strlen($value) >= (double)$argument);

        } elseif (is_array($value)) {

            return (count($value) >= (double)$argument);

        } else {

            $message = "The min rule cannot be applied to: ".var_export($value, true);
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
    public static function isMax($value, $argument)
    {
        if (is_numeric($value)) {

            return ($value <= (double)$argument);

        } elseif (is_string($value)) {

            return (mb_strlen($value) <= (double)$argument);

        } elseif (is_array($value)) {

            return (count($value) <= (double)$argument);

        } else {

            $message = "The max rule cannot be applied to: ".var_export($value, true);
            throw new \InvalidArgumentException($message);

        }
    }

// ===== PROPERTIES ============================================================
// ===== ACCESSORS =============================================================
// ===== CONSTRUCTOR ===========================================================
// ===== PUBLIC METHODS ========================================================
// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}