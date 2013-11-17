<?php
/**
 * Uac
 *
 * @author Vasiliy Horbachenko <shadow.prince@ya.ru>
 * @copyright 2013 Vasiliy Horbachenko
 * @package shadowprince/uac
 *
 */
namespace Uac\Model;

use \Autoparis\Varchar,
    \Autoparis\Id,
    \Autoparis\DateTime,
    \Autoparis\Int,
    \Autoparis\Boolean,
    \Autoparis\Text;

/**
 * User - main user model
 * Deals with username and password handling, connecting to additional uac models
 */
class User extends \Autoparis\AutoModel {
    public static $_table = "user";
    public static $profile_class = null;
    protected $perms_cache = null;

    /*
     * Get permissions array
     * @return array
     */
    public function perms() {
        if ($this->perms_cache !== null) {
            return $this->perms_cache;
        } else {
            if (strlen($this->permissions) == 0)
                $this->perms_cache = array();
            else
                $this->perms_cache = explode(":", $this->permissions);

            return $this->perms();
        }
    }

    /**
     * Add permission
     * @param string
     */
    public function addPerm($perm) {
        if (array_search($perm, $this->perms()) === false) {
            $this->permissions = implode(":", array_merge($this->perms(), array($perm)));
            $this->perms_cache = null;
            $this->perms();
        }
    }

    /**
     * Is user have $permission?
     * @param string
     * @return bool
     */
    public function hasPermission($permission) {
        if (substr($permission, 0, 1) != ".")
            $permission = "." . $permission;

        foreach ($this->perms() as $perm) {
            if (substr($permission, 0, strlen($perm)) == $perm)
                return true;
        }
    }

    /*
     * @return \Uac\Model\UserProfile
     */
    public function profile() {
        return $this->foreign_key(self::$profile_class, "user_profile_id");
    }

    public function getFields() {
        return array(
            new Id(),
            new Varchar("username", 32, array("nn" => 1)),
            new Varchar("password", 32, array("nn" => 1)),
            new Boolean("active", array("default" => true)),
            new Text("permissions"),
            new DateTime("joined", array("default" => "now")),
            new Int("user_profile_id"),
        );
    }
}
