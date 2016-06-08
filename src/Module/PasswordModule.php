<?php

namespace PP\Module;

/**
 * Description of UserModule.
 *
 * @author user
 */
class PasswordModule
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    /**
     * @var Client
     */
    public $client;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    /**
     * check min length.
     *
     * @param string $value
     * @param int    $length
     *
     * @return bool
     */
    public function validateLength($value, $length)
    {
        return strlen($value) >= $length;
    }

    /**
     * need have char.
     *
     * @param string $value
     *
     * @return bool
     */
    public function validateLetters($value)
    {
        return preg_match('/\pL/', $value);
    }

    /**
     * need have number.
     *
     * @param string $value
     *
     * @return bool
     */
    public function validateNumbers($value)
    {
        return preg_match('/\pN/', $value);
    }

    /**
     * need have diff case ie lowercase and uppercase.
     *
     * @param string $value
     *
     * @return bool
     */
    public function validateCaseDiff($value)
    {
        return preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value);
    }

    /**
     * need have Symbols.
     *
     * @param string $value
     *
     * @return bool
     */
    public function validateSymbols($value)
    {
        return preg_match('/[!@#$%^&*?()\-_=+{};:,<.>]/', $value);
    }
}
