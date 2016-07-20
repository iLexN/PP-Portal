<?php

namespace PP\Portal\AbstractClass;

/**
 * @property \Monolog\Logger $logger
 * @property \Stash\Pool $pool
 * @property \Slim\Views\Twig $twigView
 * @property \PHPMailer $mailer
 * @property \PP\Portal\Module\UserModule $UserModule
 * @property \PP\Portal\Module\PolicyModule $PolicyModule
 * @property \PP\Portal\Module\ClaimModule $ClaimModule
 * @property \PP\Portal\Module\PasswordModule $PasswordModule
 * @property \PP\Portal\Module\Helper\View $ViewHelper
 * @property array $msgCode
 */
abstract class AbstractContainer
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    public function __get($name){
        return $this->c[$name];
    }
}
