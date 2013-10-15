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

class UacTwigExtension extends \Slim\Views\TwigExtension {
    protected $app;

    public function __construct($app) {
        $this->app = $app;
    }

    public function getGlobals() {
        return array(
            "user" => $this->app->uac()->user(),
        );
    }
}