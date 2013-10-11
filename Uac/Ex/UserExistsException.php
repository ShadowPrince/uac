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

class UserExistsException extends UacException {
    public function __construct($name) {
        parent::__construct(sprintf("User \"%s\" is already exists"));
    }
}