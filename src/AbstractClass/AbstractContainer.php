<?php

namespace PP\Portal\AbstractClass;

use Illuminate\Database\Eloquent\Collection;

/**
 * @property \Monolog\Logger $logger
 * @property \Stash\Pool $pool
 * @property \Slim\Views\Twig $twigView
 * @property \PHPMailer $mailer
 * @property \PP\Portal\Module\UserModule $UserModule
 * @property \PP\Portal\Module\PolicyModule $PolicyModule
 * @property \PP\Portal\Module\UserPolicyModule $UserPolicyModule
 * @property \PP\Portal\Module\UserBankAccModule $UserBankAccModule
 * @property \PP\Portal\Module\UserPreferenceModule $UserPreferenceModule
 * @property \PP\Portal\Module\ClaimModule $ClaimModule
 * @property \PP\Portal\Module\ClaimFileModule $ClaimFileModule
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

    public function __get($name)
    {
        return $this->c[$name];
    }

    /**
     * helper function for grouping data.
     *
     * @param \Illuminate\Database\Eloquent\Collection $group
     * @param string                                   $s
     *
     * @return array
     */
    public function getDataByGroup(Collection $group, $s)
    {
        return $group->has($s) ? $group->get($s) : [];
    }
}
