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
namespace Uac;

/**
 * Vals [validators]
 * Usefull validators for dealing with uac forms
 */
class Vals {
    /**
     * Validate is user with username of given value exists
     */
    public static function userExists() {
        return function ($a, $v) {
            if ($a->fc("Uac.User")->where("username", $v)->count())
                return _("User with given username exists!");
        };
    }
}