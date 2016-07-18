<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;

/**
 * handle the password.
 *
 * @author user
 */
class PasswordModule extends AbstractContainer
{
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
        return (bool) preg_match('/\pL/', $value);
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
        return (bool) preg_match('/\pN/', $value);
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
        return (bool) preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value);
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
        return (bool) preg_match('/[!@#$%^&*?()\-_=+{};:,<.>]/', $value);
    }

    /**
     * default Passwordstrength check.
     *
     * @param string $value
     *
     * @return bool
     */
    public function isStrongPassword($value)
    {
        return $this->validateLength($value, 8) &&
                $this->validateLetters($value) &&
                $this->validateNumbers($value);
    }

    public function passwordHash($pass)
    {
        return password_hash($pass, PASSWORD_DEFAULT);
    }
}
