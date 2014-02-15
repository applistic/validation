<?php

namespace Applistic\Validation;

/**
 * Validates values based on rules.
 *
 * @author Frederic Filosa <filosa@applistic.com>
 * @copyright (c) 2014, applistic.com
 */
class Validation
{
// ===== CONSTANTS =============================================================

    const RULES_SEPARATOR           = "|";
    const RULES_ARGUMENTS_SEPARATOR = "=";
    const RULE_REQUIRED             = "required";
    const RULE_TRIM                 = "trim";
    const RULE_NO_TRIM              = "notrim";

// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================

    /**
     * The validators.
     *
     * @var array
     */
    protected $validator;

    /**
     * The values to validate.
     *
     * @var array
     */
    protected $values;

    /**
     * The validation errors.
     *
     * @var array
     */
    protected $errors;

// ===== ACCESSORS =============================================================

    /**
     * Sets the values.
     *
     * @param array $values
     */
    public function setValues(array &$values)
    {
        $this->values =& $values;
    }

    /**
     * Returns the values.
     *
     * @return null|array
     */
    public function values()
    {
        return $this->values;
    }

    /**
     * Returns the errors.
     *
     * @return null|array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Returns the errors corresponding to $valueKey.
     *
     * @param  string $valueKey The value's key.
     * @return null|array
     */
    public function valueErrors($valueKey)
    {
        if (!is_string($valueKey)) {
            $message = "\$valueKey must be a string.";
            throw new \InvalidArgumentException($message);
        }

        if (is_array($this->errors) && array_key_exists($valueKey, $this->errors)) {
            return $this->errors[$valueKey];
        } else {
            return null;
        }
    }

// ===== CONSTRUCTOR ===========================================================

    public function __construct($useReferences = true)
    {
        if (!is_bool($useReferences)) {
            $useReferences = false;
        }

        $this->useReferences = $useReferences;
        $this->validator = new StringValidator();
    }

// ===== PUBLIC METHODS ========================================================

    /**
     * Validates the values using rules.
     *
     * @param  array  $values The values.
     * @param  array  $rules  The rules.
     * @return boolean
     */
    public function validate(array &$values, array $rules)
    {
        $this->setValues($values);
        $this->validator->setValues($values);
        $this->errors = array();

        // Check other rules
        foreach ($rules as $valueKey => $valueRules) {

            if (!is_string($valueKey)) {
                $message = "\$values must contain string keys only.";
                throw new \InvalidArgumentException($message);
            }

            $r = explode(self::RULES_SEPARATOR, $valueRules);

            // Get the value and check for required, notrim and default

            $v = null;

            $requiredIndex = array_search(self::RULE_REQUIRED, $r);
            if ($requiredIndex !== false) {
                unset($r[$requiredIndex]);
            }

            $default = null;
            foreach ($r as $key => $value) {
                $defaultPos = strpos($value, 'default=');
                if ($defaultPos !== false) {
                    $default = substr($value, 8);
                    unset($r[$key]);
                    break;
                }
            }

            if (array_key_exists($valueKey, $this->values)) {

                $v = $this->values[$valueKey];

                // Check no trim
                $notrimIndex = array_search(self::RULE_NO_TRIM, $r);
                if ($notrimIndex === false) {
                    $v = trim($v);
                    $this->values[$valueKey] = $v;
                } else {
                    unset($r[$notrimIndex]);
                }

            } elseif ($requiredIndex !== false) {

                // Required and no default = error
                $this->errors[$valueKey] = self::RULE_REQUIRED;
                continue;

            } elseif (!is_null($default)) {

                // Required but default value = set value
                $this->values[$valueKey] = $default;
                continue;

            } else {

                // Not required and no default value = skip
                continue;

            }

            // Parse rules
            foreach ($r as $ruleAndArgument) {

                $separatorPos = strpos($ruleAndArgument, self::RULES_ARGUMENTS_SEPARATOR);
                if ($separatorPos !== false) {
                    $r = substr($ruleAndArgument, 0, $separatorPos);
                    $a = substr($ruleAndArgument, ($separatorPos + strlen(self::RULES_ARGUMENTS_SEPARATOR)));
                } else {
                    $r = $ruleAndArgument;
                    $a = null;
                }

                if (!$this->validator->validate($v, $r, $a)) {
                    $this->errors[$valueKey] = $r;
                    break;
                }

            }

        }

        return (count($this->errors) == 0);
    }

    /**
     * Adds a validator to the chain.
     *
     * Returns this validation object for method chaining.
     *
     * @param string|Applistic\Validation\ValidatiorInterface $validator
     * @return applistic\Validation\Validation
     */
    public function addValidator($validator)
    {
        if (is_string($validator)) {

            $validator = new $validator();

        }

        if ($validator instanceof ValidatorInterface) {

            if (is_array($this->values)) {
                $validator->setValues($this->values);
            }

            $this->validator->setLastValidator($validator);

            return $this;

        } else {

            $message  = "\$validator must represent an instance of "
                      . "Applistic\Validation\ValidatorInterface.";
            throw new \InvalidArgumentException($message);

        }
    }

// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}