<?php

use Applistic\Validation\Validation;
use Applistic\Validation\StringValidator;

class StringValidatorTest extends ApplisticValidationTestCase
{
// ===== CONSTANTS =============================================================
// ===== STATIC PROPERTIES =====================================================
// ===== STATIC FUNCTIONS ======================================================
// ===== PROPERTIES ============================================================
// ===== ACCESSORS =============================================================
// ===== CONSTRUCTOR ===========================================================
// ===== PUBLIC METHODS ========================================================

    public function testStringValidatorIsInteger()
    {
        $this->assertTrue(StringValidator::isInteger(1));
        $this->assertTrue(StringValidator::isInteger(-1));
        $this->assertTrue(StringValidator::isInteger(0));
        $this->assertTrue(StringValidator::isInteger("1"));
        $this->assertTrue(StringValidator::isInteger("-1"));
        $this->assertTrue(StringValidator::isInteger("0"));

        $this->assertFalse(StringValidator::isInteger(1.5));
        $this->assertFalse(StringValidator::isInteger(-1.5));
        $this->assertFalse(StringValidator::isInteger("1.5"));
        $this->assertFalse(StringValidator::isInteger("-1.5"));
        $this->assertFalse(StringValidator::isInteger("abcd"));
        $this->assertFalse(StringValidator::isInteger("1abcd"));
        $this->assertFalse(StringValidator::isInteger(null));
    }

    public function testStringValidatorIsBool()
    {
        $this->assertTrue(StringValidator::isBool(1));
        $this->assertTrue(StringValidator::isBool(0));
        $this->assertTrue(StringValidator::isBool(true));
        $this->assertTrue(StringValidator::isBool(false));
        $this->assertTrue(StringValidator::isBool('true'));
        $this->assertTrue(StringValidator::isBool('TRUE'));
        $this->assertTrue(StringValidator::isBool('false'));
        $this->assertTrue(StringValidator::isBool('FALSE'));
        $this->assertTrue(StringValidator::isBool('0'));
        $this->assertTrue(StringValidator::isBool('1'));

        $this->assertFalse(StringValidator::isBool('-1'));
        $this->assertFalse(StringValidator::isBool('2'));
        $this->assertFalse(StringValidator::isBool('abcd'));
        $this->assertFalse(StringValidator::isBool('bool'));
    }

    public function testStringValidatorIsMin()
    {
        $this->assertTrue(StringValidator::isMin("0", "0"));
        $this->assertTrue(StringValidator::isMin("0", 0));
        $this->assertTrue(StringValidator::isMin("1", "0"));
        $this->assertTrue(StringValidator::isMin("0.0001", "0"));
        $this->assertTrue(StringValidator::isMin("0", "-1"));
        $this->assertTrue(StringValidator::isMin("1", 0));
        $this->assertTrue(StringValidator::isMin("0.0001", 0));
        $this->assertTrue(StringValidator::isMin("0", -1));

        $this->assertFalse(StringValidator::isMin("0", "1"));
        $this->assertFalse(StringValidator::isMin("0.9999", "1"));
        $this->assertFalse(StringValidator::isMin("-1", "0"));
        $this->assertFalse(StringValidator::isMin(null, "0"));
    }

    public function testStringValidatorIsMax()
    {
        $this->assertTrue(StringValidator::isMax("0", "0"));
        $this->assertTrue(StringValidator::isMax("0", 0));
        $this->assertTrue(StringValidator::isMax("0", "1"));
        $this->assertTrue(StringValidator::isMax("0.9999", "1"));
        $this->assertTrue(StringValidator::isMax("-1", "0"));

        $this->assertFalse(StringValidator::isMax("1", "0"));
        $this->assertFalse(StringValidator::isMax("0.0001", "0"));
        $this->assertFalse(StringValidator::isMax("0", "-1"));
        $this->assertFalse(StringValidator::isMax("1", 0));
        $this->assertFalse(StringValidator::isMax("0.0001", 0));
        $this->assertFalse(StringValidator::isMax("0", -1));
        $this->assertFalse(StringValidator::isMax(null, "0"));
    }

    public function testStringValidatorIsString()
    {
        $this->assertTrue(StringValidator::isString(""));
        $this->assertTrue(StringValidator::isString("abcd"));
        $this->assertTrue(StringValidator::isString("日本語"));
        $this->assertTrue(StringValidator::isString("l'été"));
        $this->assertTrue(StringValidator::isString("صضطظع"));
        $this->assertTrue(StringValidator::isString("АБВГД"));
        $this->assertTrue(StringValidator::isString("αβγδε"));

        $this->assertFalse(StringValidator::isString(1.5));
        $this->assertFalse(StringValidator::isString(123));
        $this->assertFalse(StringValidator::isString(array()));
        $this->assertFalse(StringValidator::isString(new \stdClass()));
        $this->assertFalse(StringValidator::isString(null));
    }

    public function testStringValidatorIsLen()
    {
        $this->assertTrue(StringValidator::isLen("", 0));
        $this->assertTrue(StringValidator::isLen("abcd", "4"));
        $this->assertTrue(StringValidator::isLen("abcd", 4));
        $this->assertTrue(StringValidator::isLen("abcd ", 5));

        // This file uses UTF-8 encoding
        // If your mb_internal_encoding is different, the tests should fail
        $enc = mb_internal_encoding();
        if ($enc != 'UTF-8') {
            $this->assertFalse(StringValidator::isLen("日本語", 3, $enc));
            $this->assertFalse(StringValidator::isLen("l'été", 5, $enc));
            $this->assertFalse(StringValidator::isLen("صضطظع", 5, $enc));
            $this->assertFalse(StringValidator::isLen("АБВГД", 5, $enc));
            $this->assertFalse(StringValidator::isLen("αβγδε", 5, $enc));
        } else {
            $this->assertTrue(StringValidator::isLen("日本語", 3, $enc));
            $this->assertTrue(StringValidator::isLen("l'été", 5, $enc));
            $this->assertTrue(StringValidator::isLen("صضطظع", 5, $enc));
            $this->assertTrue(StringValidator::isLen("АБВГД", 5, $enc));
            $this->assertTrue(StringValidator::isLen("αβγδε", 5, $enc));
        }


        $this->assertFalse(StringValidator::isLen("", 1));
        $this->assertFalse(StringValidator::isLen("abc", 1));
        $this->assertFalse(StringValidator::isLen(null, 0));
    }

    public function testStringValidatorIsMinlan()
    {
        $this->assertTrue(StringValidator::isMinlen("", "0"));
        $this->assertTrue(StringValidator::isMinlen("", "-1"));
        $this->assertTrue(StringValidator::isMinlen("a", "0.5"));
        $this->assertTrue(StringValidator::isMinlen("abcd", "4"));
        $this->assertTrue(StringValidator::isMinlen("abcd", "0"));
        $this->assertTrue(StringValidator::isMinlen("abcd", "-1"));

        $this->assertFalse(StringValidator::isMinlen("", 1));
        $this->assertFalse(StringValidator::isMinlen("abc", 5));
        $this->assertFalse(StringValidator::isMinlen(null, 0));

        // This file uses UTF-8 encoding
        $enc = mb_internal_encoding();
        if ($enc != 'UTF-8') {
            $this->assertTrue(StringValidator::isMinlen("日本語", "3", 'UTF-8'));
            $this->assertFalse(StringValidator::isMinlen("日本語", "5", 'UTF-8'));
        } else {
            $this->assertTrue(StringValidator::isMinlen("日本語", "3"));
            $this->assertFalse(StringValidator::isMinlen("日本語", "5"));
        }
        $this->assertTrue(StringValidator::isMinlen("日本語", "0"));
    }

    public function testStringValidatorIsMaxlen()
    {
        $this->assertTrue(StringValidator::isMaxlen("", "0"));
        $this->assertTrue(StringValidator::isMaxlen("abcd", "4"));
        $this->assertTrue(StringValidator::isMaxlen("", 1));
        $this->assertTrue(StringValidator::isMaxlen("abc", 5));

        $this->assertFalse(StringValidator::isMaxlen("", "-1"));
        $this->assertFalse(StringValidator::isMaxlen("abcd", "0"));
        $this->assertFalse(StringValidator::isMaxlen("abcd", "-1"));
        $this->assertFalse(StringValidator::isMaxlen(null, 0));

        // This file uses UTF-8 encoding
        $enc = mb_internal_encoding();
        if ($enc != 'UTF-8') {
            $this->assertTrue(StringValidator::isMaxlen("日本語", "3", 'UTF-8'));
            $this->assertTrue(StringValidator::isMaxlen("日本語", "5", 'UTF-8'));
        } else {
            $this->assertTrue(StringValidator::isMaxlen("日本語", "3"));
            $this->assertTrue(StringValidator::isMaxlen("日本語", "5"));
        }
        $this->assertFalse(StringValidator::isMaxlen("日本語", "0"));
    }

    public function testStringValidatorIsInWithItemsAsString()
    {
        $itemsStringComma = ",name,first_name,last_name,英語,日本語,abcd,1234,";

        $this->assertTrue(StringValidator::isIn("name", $itemsStringComma));
        $this->assertTrue(StringValidator::isIn("last_name", $itemsStringComma));
        $this->assertTrue(StringValidator::isIn("1234", $itemsStringComma));
        $this->assertTrue(StringValidator::isIn("abcd", $itemsStringComma));
        $this->assertTrue(StringValidator::isIn("日本語", $itemsStringComma));

        $this->assertFalse(StringValidator::isIn("ame", $itemsStringComma));
        $this->assertFalse(StringValidator::isIn("name ", $itemsStringComma));
        $this->assertFalse(StringValidator::isIn(" name", $itemsStringComma));
        $this->assertFalse(StringValidator::isIn(" name ", $itemsStringComma));
        $this->assertFalse(StringValidator::isIn("1", $itemsStringComma));

        $itemsStringSharp = "#name#first_name#last_name#日本語#abcd#1234#,#";

        $this->assertTrue(StringValidator::isIn("name", $itemsStringSharp, "#"));
        $this->assertTrue(StringValidator::isIn("last_name", $itemsStringSharp, "#"));
        $this->assertTrue(StringValidator::isIn("1234", $itemsStringSharp, "#"));
        $this->assertTrue(StringValidator::isIn("abcd", $itemsStringSharp, "#"));
        $this->assertTrue(StringValidator::isIn(",", $itemsStringSharp, "#"));
        $this->assertTrue(StringValidator::isIn("日本語", $itemsStringSharp, "#"));

        $this->assertFalse(StringValidator::isIn("ame", $itemsStringSharp, "#"));
        $this->assertFalse(StringValidator::isIn("1", $itemsStringSharp, "#"));
    }

    public function testStringValidatorIsInWithItemsAsArray()
    {
        $items = array("name","first_name","last_name","日本語","abcd","1234");

        $this->assertTrue(StringValidator::isIn("name", $items));
        $this->assertTrue(StringValidator::isIn("last_name", $items));
        $this->assertTrue(StringValidator::isIn("1234", $items));
        $this->assertTrue(StringValidator::isIn("abcd", $items));

        $this->assertFalse(StringValidator::isIn("ame", $items));
        $this->assertFalse(StringValidator::isIn("1", $items));
    }

// ===== PROTECTED METHODS =====================================================
// ===== PRIVATE METHODS =======================================================
}