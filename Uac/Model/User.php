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

    public function perms() {
        if ($this->perms_cache !== null) {
            return $this->perms_cache;
        } else {
            if (strlen($this->permissions) == 0)
                $this->perms_cache = [];
            else
                $this->perms_cache = explode(":", $this->permissions);

            return $this->perms();
        }
    }

    public function addPerm($perm) {
        if (array_search($perm, $this->perms()) === false) {
            $this->permissions = implode(":", array_merge($this->perms(), [$perm]));
            $this->perms_cache = null;
            $this->perms();
        }
    }

    public function hasPermission($permission) {
        if (substr($permission, 0, 1) != ".")
            $permission = "." . $permission;

        foreach ($this->perms() as $perm) {
            if (substr($permission, 0, strlen($perm)) == $perm)
                return true;
        }
    }

    public function profile() {
        return $this->foreign_key(self::$profile_class, "user_profile_id");
    }

    public function getFields() {
        return [
            new Id(),
            new Varchar("username", 32, ["nn" => 1]),
            new Varchar("password", 32, ["nn" => 1]),
            new Boolean("active", ["default" => true]),
            new Text("permissions"),
            new DateTime("joined", ["default" => "now"]),
            new Int("user_profile_id"),
        ];
    }
}
