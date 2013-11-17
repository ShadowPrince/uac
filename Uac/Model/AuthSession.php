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
        return array(
            new Id(),
            new Int("user_id", array("nn" => 1)),
            new Varchar("identifier", 32, array("nn" => 1)),
            new Varchar("ip", 96),
            new Text("ua"),
            new DateTime("started", array("default" => "now")),
            new DateTime("updated", array("auto" => "now")),
        );
    }
}
