<?php
/**
 * Uac
 *
 * @author Vasiliy Horbachenko <shadow.prince@ya.ru>
 * @copyright 2013 Vasiliy Horbachenko
 * @package shadowprince/uac
 *
 */
namespace Uac;

/** 
 * Middleware - usefull slim's midlewares for dealing with uac
 */
class Middleware {
    /**
     * Check if user is logged, else redirect him to login page
     */
    public static function loginRequired($app) {
        return function () use ($app) {
            if ($app->uac()->user() === false) {
                $app->flash("error", _("Authorization required!"));
                $app->redirect($app->config("uac.login_url"));
                return false;
            } else return true;
        };
    }

    /**
     * Check if user is NOT logged, else redirect him to profile and flash
     */
    public static function guestRequired($app) {
        return function () use ($app) {
            if ($app->uac()->user() !== false) {
                $app->flash("error", _("Only non-authorized users are allowed!"));
                $app->redirect($app->config("uac.profile_url"));
                return false;
            } else return true;
        };
    }

    /**
     * Check if user logged and has $perm[ission]
     * @TODO: fix
     */
    public static function permRequired($app, $perm) {
        return function () use ($app, $perm) {
            if (call_user_func(self::loginRequired($app), $app) && !$app->uac()->user()->hasPermission($perm)) {
                $app->flash("error", _("You dont have permissions!"));
                $app->redirect($app->config("uac.profile_url"));
                return false;
            }

            return true;
        };
    }
}