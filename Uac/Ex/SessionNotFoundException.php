<?php
/**
 * Uac
 *
 * @author Vasiliy Horbachenko <shadow.prince@ya.ru>
 * @copyright 2013 Vasiliy Horbachenko
 * @package shadowprince/uac
 *
 */
namespace Uac\Ex;

class SessionNotFoundException extends SessionException {
    public function __construct($sessid) {
        parent::__construct(sprintf("Session %s not found", $sessid));
    }
}