<?php
/**
 * @brief This is a class designed to handle CSRFTokens to secure a web application
 *        from CSRF attacks. It is part of a video tutorial on how to defeat CSRF
 *        attacks. This class also assumes you will handle starting sessions
 *        outside of this class.
 * @author Richard Williamson <richard@nilfactor.com>
 * @website http://nilfactor.com/
 */

/**
 * simply allows us to create an unique exception to allow us to easily tell what
 * kind of error happened, and if tests where find we can generally ignore this
 * error as we want to see it happening
 */
final class NilFactorCSRFTokenException extends Exception { }
final class NilFactorCSRFNoTokenException extends Exception { }

final class NilFactorCSRFToken {
    /**
     * @var string KEY_NAME - the name of the key variable we are looking for
     */
    CONST KEY_NAME = 'csrf_token';

    /**
     * @var array IMMUNE_METHODS - array of http methods that we won't tokens for
     */
    CONST IMMUNE_METHODS = ['GET'];

    /**
     * @var boolean FORCE_NEW_TOKEN - force a new CSRF Token every request
     */
    CONST FORCE_NEW_TOKEN = false;

    /**
     * @brief create a new token for this end users session
     */
    final public static function generateNewSessionToken() {
        // generate the token
        $token = md5(uniqid(rand(), TRUE));
        $_SESSION[static::KEY_NAME] = $token;
    }

    /**
     * @brief verify that the current request is valid
     */
    final public static function verifyRequest() {
        if (empty($_SESSION[static::KEY_NAME])) {
            throw new NilFactorCSRFNoTokenException('Session has no CSRF token');
        }

        $expected_token = $_SESSION[static::KEY_NAME];

        if (empty($_REQUEST[static::KEY_NAME]) || $expected_token !== $_REQUEST[static::KEY_NAME]) {
            throw new NilFactorCSRFTokenException('CSRF token validation failed');
        }
    }

    /**
     * @brief this is where the magic happens we need to handle the session create
     *        the CSRF Token if it doesn't exist, or deny requests if its missing
     */
    final public static function handleSession() {
        if (in_array($_SERVER['REQUEST_METHOD'], static::IMMUNE_METHODS) && static::FORCE_NEW_TOKEN === false) {
            // the token hasn't been created
            if (empty($_SESSION[static::KEY_NAME])) {
                static::generateNewSessionToken();
            }
        } else if (in_array($_SERVER['REQUEST_METHOD'], static::IMMUNE_METHODS) === false) {
            static::verifyRequest();
        }

        // we want to force a new token with every request
        if (static::FORCE_NEW_TOKEN) {
            static::generateNewSessionToken();
        }
    }

    /**
     * @brief return the token value for this session
     */
    final public static function getTokenValue() {
        return !empty($_SESSION[static::KEY_NAME]) ? $_SESSION[static::KEY_NAME] : '';
    }
}
