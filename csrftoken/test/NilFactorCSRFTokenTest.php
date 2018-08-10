<?php
use PHPUnit\Framework\TestCase;
require_once('NilFactorCSRFToken.php');

final class NilFactorCSRFTokenTest extends TestCase {
    public function setUp() {
        if (!empty($_SESSION[NilFactorCSRFToken::KEY_NAME])) {
            unset($_SESSION[NilFactorCSRFToken::KEY_NAME]);
        }

        $_REQUEST = [];
    }

    public function testGenerateNewSessionToken() {
        NilFactorCSRFToken::generateNewSessionToken();

        $this->assertTrue(!empty($_SESSION[NilFactorCSRFToken::KEY_NAME]), "Token value should have been set in the session");
    }

    /**
     * @expectedException NilFactorCSRFNoTokenException
     */
    public function testVerifyRequestRequiresTokenFailEmpty() {
        NilFactorCSRFToken::verifyRequest();
    }

    /**
     * @expectedException NilFactorCSRFTokenException
     */
    public function testVerifyRequestRequiresTokenFailEmptyRequest() {
        $_SESSION[NilFactorCSRFToken::KEY_NAME] = "test";
        NilFactorCSRFToken::verifyRequest();
    }

    /**
     * @expectedException NilFactorCSRFTokenException
     */
    public function testVerifyRequestRequiresTokenFailMismatch() {
        $_SESSION[NilFactorCSRFToken::KEY_NAME] = "test";
        $_REQUEST[NilFactorCSRFToken::KEY_NAME] = "bob";
        NilFactorCSRFToken::verifyRequest();
    }

    public function testVerifyRequestRequiresTokenPass() {
        $_SESSION[NilFactorCSRFToken::KEY_NAME] = "test";
        $_REQUEST[NilFactorCSRFToken::KEY_NAME] = "test";

        try {
            NilFactorCSRFToken::verifyRequest();
        } catch (InvalidArgumentException $notExpected) {
            $this->fail();
        }

        $this->assertTrue(TRUE);
    }

    public function testHandleSessionGet() {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        NilFactorCSRFToken::handleSession();

        $this->assertTrue(!empty($_SESSION[NilFactorCSRFToken::KEY_NAME]), "Token value should have been set in the session");
    }

    /**
     * @expectedException NilFactorCSRFTokenException
     */
    public function testHandleSessionPostFailureEmpty() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION[NilFactorCSRFToken::KEY_NAME] = "test";

        NilFactorCSRFToken::handleSession();
    }

    /**
     * @expectedException NilFactorCSRFTokenException
     */
    public function testHandleSessionPostFailureMismatch() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION[NilFactorCSRFToken::KEY_NAME] = "test";
        $_REQUEST[NilFactorCSRFToken::KEY_NAME] = "bob";

        NilFactorCSRFToken::handleSession();
    }

    public function testGetTokenValueReturnsEmptyString() {
        $expected = '';
        $actual = NilFactorCSRFToken::getTokenValue();

        $this->assertEquals($expected, $actual, 'The token should be empty');
    }

    public function testGetTokenValueReturnsString() {
        $expected = 'test';
        $_SESSION[NilFactorCSRFToken::KEY_NAME] = $expected;
        $actual = NilFactorCSRFToken::getTokenValue();

        $this->assertEquals($expected, $actual, 'The token should match expected');
    }
}