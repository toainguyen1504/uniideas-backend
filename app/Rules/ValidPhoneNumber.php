<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    protected $prefixTenNumers = '086|096|097|098|088|090|093|089|091|094|092|039|038|037|036|035|034|033|032|070|079|077|076|078|083|084|085|081|082|056|058|059';
    protected $prefixElevenNumbers = '0162|0163|0164|0165|0166|0167|0168|0169|0120|0121|0122|0126|0128|0911|0941|0123|0124|0125|0127|0129|0188|0186|0993|0994|0996|0199|0995|0997';
    protected $errorType;

    protected const ERROR_TYPE_FORMAT = 1;
    protected const ERROR_TYPE_LENGTH = 2;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->checkPasses($attribute, $value)) {
            $fail($this->messageError());
        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function checkPasses($attribute, $value)
    {
        $value = $this->removeCountryCode($value);

        $phoneLength = strlen($value);
        if ($phoneLength < 10 || $phoneLength > 11) {
            $this->errorType = self::ERROR_TYPE_LENGTH;
            return false;
        }

        if ($phoneLength === 10 && !preg_match('/^' . $this->prefixTenNumers . '/', $value)) {
            $this->errorType = self::ERROR_TYPE_FORMAT;
            return false;
        }

        if ($phoneLength === 11 && !preg_match('/^' . $this->prefixElevenNumbers . '/', $value)) {
            $this->errorType = self::ERROR_TYPE_FORMAT;
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function messageError()
    {
        switch ($this->errorType) {
            case self::ERROR_TYPE_LENGTH:
                $msg = __('Phone number must be 10 or 11 digits long after removing country code.');
                break;
            default:
                $msg = __('Phone number format is invalid. It must start with a valid prefix and contain only digits.');
                break;
        }
        return $msg;
    }

    protected function removeCountryCode($phone)
    {
        if (preg_match('/^\+84/', $phone)) {
            $phone = preg_replace('/^\+84/', '', $phone);
            if (!preg_match('/^0/', $phone)) {
                $phone = '0' . $phone;
            }
        }
        return $phone;
    }
}
