<?php
/**
 * FShare
 *
 * @author Vasiliy Horbachenko <shadow.prince@ya.ru>
 * @copyright 2013 Vasiliy Horbachenko
 * @version 1.0
 * @package FShare
 *
 */
namespace Uac\Ex;

class CredentialsInvalidException extends UacException {
    public function __construct() {
        parent::__construct("Credentials invalid");
    }
}