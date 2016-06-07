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

    private function validateLength($value){
        return strlen($value) > 6;
    }

    private function validateLetters($value)
    {
        return preg_match('/\pL/', $value);
    }

    private function validateNumbers($value)
    {
        return preg_match('/\pN/', $value);
    }

    private function validateCaseDiff($value)
    {
        return preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value);
    }
    
    private function validateSymbols($value)
    {
        return preg_match('/[!@#$%^&*?()\-_=+{};:,<.>]/', $value);
    }
}
