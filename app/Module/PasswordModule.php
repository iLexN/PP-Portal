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
     * pass the password value to check by rules
     * @param string $value password
     * @param array $rules
     * @return boolean
     */
    public function checkPasswordstrength($value,$rules = []){
        $error = [];

        foreach ( $rules as $rule ){
            $error[] = $this->checkPasswordRule($value,$rule);
        }

        $this->c->logger->info('checkPasswordstrength',$error);

        if (in_array(false, $error)) {
            return false;
        }

        return true;
    }

    /**
     * check by rule
     * @param string $value
     * @param string $rule
     * @return boolean
     */
    private function checkPasswordRule($value,$rule){
        switch ($rule) {
            case 'letters':
                return $this->validateLetters($value);
            case 'numbers':
                return $this->validateNumbers($value);
            case 'casediff':
                return $this->validateCaseDiff($value);
            case 'symboles':
                return $this->validateSymbols($value);
            case 'length':
                return $this->validateLength($value);
            default:
                return false;
        }
    }

    /**
     * check min length
     * @param string $value
     * @return bool
     */
    private function validateLength($value){
        return strlen($value) > 6;
    }

    /**
     * need have char
     * @param string $value
     * @return bool
     */
    private function validateLetters($value)
    {
        return preg_match('/\pL/', $value);
    }

    /**
     * need have number
     * @param string $value
     * @return bool
     */
    private function validateNumbers($value)
    {
        return preg_match('/\pN/', $value);
    }

    /**
     * need have diff case ie lowercase and uppercase
     * @param string $value
     * @return bool
     */
    private function validateCaseDiff($value)
    {
        return preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value);
    }

    /**
     * need have Symbols
     * @param string $value
     * @return bool
     */
    private function validateSymbols($value)
    {
        return preg_match('/[!@#$%^&*?()\-_=+{};:,<.>]/', $value);
    }
}
