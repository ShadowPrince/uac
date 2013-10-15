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

class SessionExpiredException extends SessionException {
    public function __construct($session) {
        parent::__construct(sprintf("Session %d expired", $session->id));
    }
}