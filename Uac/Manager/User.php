<?php
/**
 * Uac
 *
 * @author Vasiliy Horbachenko <shadow.prince@ya.ru>
 * @copyright 2013 Vasiliy Horbachenko
 * @version 1.0
 * @package shadowprince/uac
 *
 */
namespace Uac\Manager;

/**
 * User - manager for users
 * Contains static methods to deal with creating, updating and processing
 */
class User {
    /**
     * Login with auth session
     * @param \SlimExt\SlimExt
     * @return \Uac\Model\User
     */
    public static function sessionLogin($app, $identifier) {
        $session = $app->fc("Uac.AuthSession")
            ->where("identifier", $identifier)
            ->find_one();

        if ($session === false)
            throw new \Uac\Ex\SessionNotFoundException($identifier);

        $diff = (new \DateTime())->getTimestamp() - $session->updated->getTimestamp();

        try {
            if ($diff > $app->config("uac.session_live_time")) {
                throw new \Uac\Ex\SessionExpiredException($session);
            }

            $user = $session->user();

            if ($user === false) {
                throw new \Uac\Ex\SessionInvalidException($session);
            }

            if (!$user->active) {
                throw new \Uac\Ex\UserNotActiveException();
            }

            $session->ip = $app->request()->getIp();
            $session->ua = $app->request()->getUserAgent();
            $session->save();

            return $user;
        } catch (\Exception $e) {
            $session->delete();
            throw $e;
        }
    }

    /**
     * Install session (setup session and put it to cookies
     * @param \SlimExt\SlimExt
     * @param \Uac\Model\User
     */
    public static function installSession($app, $user) {
        $session = $app->fc("Uac.AuthSession")->create([
            "user_id" => $user->id,
            "identifier" => self::genSessionIdentifier($user, $app->config("secret")),
            "ip" => $app->request()->getIp(),
            "ua" => $app->request()->getUserAgent(),
        ]);
        $session->save();

        $app->setEncryptedCookie(
            "uac_sessid", 
            $session->identifier, 
            $app->config("uac.session_cookie_live_time")
        );
    }

    /**
     * Logout $user (remove current session)
     * @param \SlimExt\SlimExt
     * @param \Uac\Model\User
     */
    public static function logout($app, $identifier) {
        $session = $app->fc("Uac.AuthSession")
            ->where("identifier", $identifier)
            ->find_one();

        $session->delete();
    }

    /**
     * Logout $user from all sessions
     * @param \SlimExt\SlimExt
     * @param \Uac\Model\User
     */
    public static function logoutAll($app, $user) {
        $app->fc("Uac.AuthSession")
            ->where("user_id", $user->id)
            ->delete_many();
    }

    /**
     * Login user with credentials
     * @param \SlimExt\SlimExt
     * @param string
     * @param string
     * @throws \Uac\Ex\CredentialsInvalidException
     * @return \Uac\Model\User
     */
    public static function credentialsLogin($app, $username, $password) {
        $user = $app->fc("Uac.User")
            ->where("username", $username)
            ->where("password", self::encryptPassword($password, $app->config("secret")))
            ->find_one();

        try {
            if ($user === false)
                throw new \Uac\Ex\CredentialsInvalidException();
            if (!$user->active)
                throw new \Uac\Ex\UserNotActiveException();

            return $user;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /** 
     * Create user
     * Method DONT CHECK any data or user existence!
     * @param \SlimExt\SlimExt
     * @param string
     * @param string
     * @return \Uac\Model\User
     */
    public static function create($app, $username, $password) {
        $profile = $app->fc($app->config("uac.user_profile"))->create();
        $profile->save();

        $user = $app->fc("Uac.User")->create([
            "username" => $username,
            "password" => self::encryptPassword($password, $app->config('secret')),
            "user_profile_id" => $profile->id,
        ]);

        $user->save();
        return $user;
    }

    /** 
     * Delete user and all associated data
     * @param \SlimExt\SlimExt
     * @param \Uac\Model\User
     */
    public static function delete($app, $user) {
        $app->fc("Uac.AuthSession")->where("user_id", $user->id)->delete_many();
        $user->profile()->delete();
        $user->delete();
    }

    /**
     * Generate session identifier
     * @param \Uac\Model\User
     * @param string
     * @return string
     */
    public static function genSessionIdentifier($user, $salt) {
        return md5(""
            . $user->username
            . ":"
            . rand(100, 1000)
            . ":"
            . (new \DateTime())->getTimestamp()
            . ":"
            . $salt
        );
    }

    /**
     * Encrypt passworld
     * @param string
     * @param string
     * @return string
     */
    public static function encryptPassword($raw, $salt) {
        return md5(""
            . md5($raw)
            . ":"
            . $salt
        );
    }
}