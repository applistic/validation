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
    const RULE_EXISTS               = "exists";
    const RULE_NOT_EMPTY            = "notEmpty";
    const RULE_TRIM                 = "trim";

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

    /**
     * Defines if $values are kept as references.
     *
     * @var boolean
     */
    protected $useReferences;

// ===== ACCESSORS =============================================================

    /**
     * Sets the values.
     *
     * @param array $values
     */
    public function setValues(array &$values)
    {
        if ($this->useReferences) {
            $this->values =& $values;
        } else {
            $this->values = $values;
        }
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
        $this->validator = new BaseValidator();
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

        foreach ($rules as $valueKey => $valueRules) {

            if (!is_string($valueKey)) {
                $message = "\$values must contain string keys only.";
                throw new \InvalidArgumentException($message);
            }

            $r = explode(self::RULES_SEPARATOR, $valueRules);

            // If the value doesn't exist, no need to check the other rules.
            if (!$this->checkExists($valueKey, $r)) {
                continue;
            }

            // Trim the strings if necessary
            $this->checkTrim($valueKey, $r);

            // Parse rules
            foreach ($r as $ruleAndArgument) {

                $this->checkRule($valueKey, $ruleAndArgument);

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

            $message  = "\$validator must represent an instance of ";
            $message .= "Applistic\Validation\ValidatorInterface.";
            throw new \InvalidArgumentException($message);

        }
    }

// ===== PROTECTED METHODS =====================================================

    /**
     * Checks if the value exists.
     *
     * @param  string $valueKey The value key.
     * @param  array  $rules    The rules.
     * @return boolean
     */
    protected function checkExists($valueKey, array &$rules)
    {
        $exists = array_key_exists($valueKey, $this->values);

        $keyIndex = array_search(self::RULE_EXISTS, $rules);

        if ($keyIndex !== false) {

            if (!$exists) {
                $this->errors[$valueKey][] = self::RULE_EXISTS;
            }

            unset($rules[$keyIndex]);

        }

        return $exists;
    }

    /**
     * Trim the strings if necessary.
     *
     * @param  string $valueKey The value key.
     * @param  array  $rules    The rules.
     * @return boolean
     */
    protected function checkTrim($valueKey, array &$rules)
    {
        $keyIndex = array_search(self::RULE_TRIM, $rules);

        if ($keyIndex !== false) {

            $value = $this->values[$valueKey];

            if (is_string($value)) {

                $this->values[$valueKey] = trim($value);

            }

            unset($rules[$keyIndex]);

        }
    }

    /**
     * Performs validation of a rule.
     *
     * @param  string $valueKey [description]
     * @param  string $rule     [description]
     * @return boolean
     */
    protected function checkRule($valueKey, $rule)
    {
        $ruleParts = explode(self::RULES_ARGUMENTS_SEPARATOR, $rule);
        $count = count($ruleParts);

        $v = $this->values[$valueKey];
        $r = array_shift($ruleParts);
        $a = (count($ruleParts) > 0 ? implode(self::RULES_ARGUMENTS_SEPARATOR, $ruleParts) : null);

        if ($this->validator->validate($v, $r, $a)) {
            return true;
        } else {
            $this->errors[$valueKey] = $r;
            return false;
        }
    }

// ===== PRIVATE METHODS =======================================================
}