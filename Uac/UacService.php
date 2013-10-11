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
 * Uac - UserACcounts
 * Main class for uac service
 */
class UacService extends \SlimExt\SlimService {
    protected $user = null;
    protected $cause = false;
    protected $permissions = [];

    public function __construct($app) {
        $this->defaultConfig($app, [
            "uac.route_prefix" => "/uac/",

            "uac.session_live_time" => 7 * 24 * 60 * 60,
            "uac.session_cookie_live_time" => "7 days",
            "uac.user_profile" => "Uac.UserProfile",

            "uac.login_url" => "/uac/login/",
            "uac.profile_url" => "/uac/user/",
        ]);

        \Uac\Model\User::$profile_class = $app->getCC($app->config("uac.user_profile"));
        $app->view()->getTwig()->addExtension(new \Uac\UacTwigExtension($app));

        $app->hook("slim.before", function () use ($app) {
            $this->cookieLogin($app);
        });
    }

    /**
     * Authenticate user with value from cookies
     * @param \SlimExt\SlimExt
     */
    public function cookieLogin($app) {
        try {
            $this->user = Manager\User::sessionLogin(
                $app,
                $app->getEncryptedCookie("uac_sessid")
            );
        } catch (\Uac\Ex\SessionException $e) {
            $this->user = false;
            $this->cause = _("Session expired or invalid!");
        } catch (\Uac\Ex\UserNotActiveException $e) {
            $this->user = false;
            $this->cause = _("Can't login, user not active!");
        }
    }

    /**
     * Register permission in uac system
     * @param string
     */
    public function registerPerm($perm) {
        $this->permissions[] = $perm;
    }

    /**
     * Is user logged?
     * @return bool
     */
    public function logged() {
        return $this->user !== false;
    }

    /**
     * Get currently logged user or null
     * @return \Uac\Model\User
     */
    public function user() {
        return $this->user;
    }
}
