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
namespace Uac\Model;

use \Autoparis\Id,
    \Autoparis\Int,
    \Autoparis\Text,
    \Autoparis\DateTime,
    \Autoparis\Varchar;

/**
 * AuthSession - handles information about active sessions (user authentication)
 * Connects with \Uac\Model\User by foreign key
 */
class AuthSession extends \Autoparis\AutoModel {
    public static $_table = "auth_session";

    /**
     * @return \Uac\Model\User
     */
    public function user() {
        return $this->foreign_key(__NAMESPACE__ . "\\User");
    }

    public function getFields() {
        return [new Id(),
            new Int("user_id", ["nn" => 1]),
            new Varchar("identifier", 32, ["nn" => 1]),
            new Varchar("ip", 96),
            new Text("ua"),
            new DateTime("started", ["default" => "now"]),
            new DateTime("updated", ["auto" => "now"]),
        ];
    }
}
