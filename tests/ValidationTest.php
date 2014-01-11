<?php

use Applistic\Validation\Validation;
use Applistic\Validation\BaseValidator;

class ValidationTest extends ApplisticValidationTestCase
{
// ===== CONSTANTS =============================================================
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================
// ===== ACCESSORS =============================================================
// ===== CONSTRUCTOR ===========================================================
// ===== PUBLIC METHODS ========================================================

    public function testInstanciation()
    {
        $validation = new Validation();
        $this->assertTrue(is_a($validation, "Applistic\Validation\Validation"));
    }

    public function testValidAddedValidatorAsString()
    {
        $validation = new Validation();
        $validation->addValidator("Applistic\Validation\BaseValidator");
    }

    public function testValidAddedValidatorAsObject()
    {
        $baseValidator = new BaseValidator();

        $validation = new Validation();
        $validation->addValidator($baseValidator);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidAddedValidatorAsObject()
    {
        $validation = new Validation();
        $validation->addValidator(new stdClass());
    }

// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}