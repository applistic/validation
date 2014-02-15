<?php

namespace Applistic\Validation;

/**
 * Provides validation for strings.
 *
 * This validator uses the mbstring library.
 *
 * @author Frederic Filosa <filosa.frederic@gmail.com>
 * @copyright (c) 2014, Frederic Filosa
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class StringValidator extends AbstractValidator
{
// ===== CONSTANTS =============================================================
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================

    /**
     * Validates as a string.
     *
     * @param  string  $v
     * @return boolean
     */
    public static function isString($v)
    {
        return is_string($v);
    }

    /**
     * Validates as an integer.
     *
     * @param  string  $v
     * @return boolean
     */
    public static function isInteger($v)
    {
        return (bool)preg_match('/^-{0,1}[0-9]+$/', $v);
    }

    /**
     * Validates as a numeric.
     *
     * @param  string  $v
     * @return boolean
     */
    public static function isNumeric($v)
    {
        return is_numeric($v);
    }

    /**
     * Validates as a boolean.
     *
     * @param  string  $v
     * @return boolean
     */
    public static function isBool($v)
    {
        if (is_string($v)) {
            $items = ",0,1,true,false,TRUE,FALSE,";
            return (strpos($items, ",".$v.",") !== false);
        } elseif (is_int($v)) {
            return (($v == 0) || ($v == 1));
        } else {
            return is_bool($v);
        }
    }

    /**
     * Validates the minimum value of a numeric.
     *
     * @param  string  $v
     * @param  numeric $min
     * @return boolean
     */
    public static function isMin($v, $min)
    {
        if (is_string($v)) {
            return ((double)$v >= (double)$min);
        } else {
            return false;
        }
    }

    /**
     * Validates the maximum value of a numeric.
     *
     * @param  string  $v
     * @param  numeric $max
     * @return boolean
     */
    public static function isMax($v, $max)
    {
        if (is_string($v)) {
            return ((double)$v <= (double)$max);
        } else {
            return false;
        }
    }

    /**
     * Validates the exact length of a string.
     *
     * @param  string  $v
     * @param  numeric $len
     * @param  string  $encoding Optional encoding.
     *                           Default is the value of mb_internal_encoding()
     * @return boolean
     */
    public static function isLen($v, $len, $encoding = null)
    {
        if (is_string($v)) {
            if (is_string($encoding)) {
                return (mb_strlen($v, $encoding) == (int)$len);
            } else {
                return (mb_strlen($v) == (int)$len);
            }
        } else {
            return false;
        }
    }

    /**
     * Validates the minimum length of a string.
     *
     * @param  string  $v
     * @param  numeric $len
     * @param  string  $encoding Optional encoding.
     *                           Default is the value of mb_internal_encoding()
     * @return boolean
     */
    public static function isMinlen($v, $len, $encoding = null)
    {
        if (is_string($v)) {
            if (is_string($encoding)) {
                return (mb_strlen($v, $encoding) >= (int)$len);
            } else {
                return (mb_strlen($v) >= (int)$len);
            }
        } else {
            return false;
        }
    }

    /**
     * Validates the minimum length of a string.
     *
     * @param  string  $v
     * @param  numeric $len
     * @param  string  $encoding Optional encoding.
     *                           Default is the value of mb_internal_encoding()
     * @return boolean
     */
    public static function isMaxlen($v, $len, $encoding = null)
    {
        if (is_string($v)) {
            if (is_string($encoding)) {
                return (mb_strlen($v, $encoding) <= (int)$len);
            } else {
                return (mb_strlen($v) <= (int)$len);
            }
        } else {
            return false;
        }
    }

    /**
     * Validates that the string is one of the provided items.
     *
     * The preferred way to use this function is to provide a string of items.
     * Searching by strpos provides better performance than in_array.
     * When using a string for items, just be sure to prepend and append the
     * separator to the items:
     *
     *     $items = ",name,first_name,last_name,full_name,"; // Right
     *     $items = "name,first_name,last_name,full_name";   // Wrong
     *
     *     // We'll search for `,name,` so that other items containing `name`
     *     // will be ignored.
     *
     * @param  string  $v
     * @param  string|array  $items
     * @return boolean
     */
    public static function isIn($v, $items, $separator = ",")
    {
        if (is_string($items)) {
            return (strpos($items, $separator.$v.$separator) !== false);
        } elseif (is_array($items)) {
            return in_array($v, $items);
        } else {
            return false;
        }
    }

// ===== PROPERTIES ============================================================
// ===== ACCESSORS =============================================================
// ===== CONSTRUCTOR ===========================================================
// ===== PUBLIC METHODS ========================================================
// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}