<?php
/**
 * Uac
 *
 * @author Vasiliy Horbachenko <shadow.prince@ya.ru>
 * @copyright 2013 Vasiliy Horbachenko
 * @version 1.0
 * @package uac
 *
 */
namespace Uac\Ex;

class UserNotActiveException extends UacException {
    public function __construct() {
        parent::__construct("User not active!");
    }
}