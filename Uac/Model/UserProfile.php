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

use \Autoparis\Text,
    \Autoparis\Id;

/**
 * UserProfile - for dealing with user-profiles, without editing Uac itself
 * EXAMPLE
 */
class UserProfile extends \Autoparis\AutoModel {
    public static $_table = "user_profile";

    public function getFields() {
        return [
            new Id(),
            new Text("bio"),
        ];
    } 
}
