<?php

namespace Payum\Core\Model;

use DateTime;
use Payum\Core\Exception\InvalidArgumentException;
use Payum\Core\Security\SensitiveValue;
use Payum\Core\Security\Util\Mask;

class CreditCard implements CreditCardInterface
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $brand;

    /**
     * @var string
     */
    protected $holder;

    /**
     * @var SensitiveValue
     *
     * @deprecated
     */
    protected $securedHolder;

    /**
     * @var string
     */
    protected $maskedHolder;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var SensitiveValue
     *
     * @deprecated
     */
    protected $securedNumber;

    /**
     * @var string
     */
    protected $maskedNumber;

    /**
     * @var string
     */
    protected $securityCode;

    /**
     * @var SensitiveValue
     *
     * @deprecated
     */
    protected $securedSecurityCode;

    /**
     * @var DateTime
     */
    protected $expireAt;

    /**
     * @var SensitiveValue
     *
     * @deprecated
     */
    protected $securedExpireAt;

    public function __construct()
    {
        $this->securedHolder = SensitiveValue::ensureSensitive(null);
        $this->securedSecurityCode = SensitiveValue::ensureSensitive(null);
        $this->securedNumber = SensitiveValue::ensureSensitive(null);
        $this->securedExpireAt = SensitiveValue::ensureSensitive(null);
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setHolder($holder)
    {
        $this->securedHolder = SensitiveValue::ensureSensitive($holder);
        $this->maskedHolder = Mask::mask($this->securedHolder->peek());

        // BC
        $this->holder = $this->securedHolder->peek();
    }

    public function getHolder()
    {
        return $this->securedHolder->peek();
    }

    public function setMaskedHolder($maskedHolder)
    {
        $this->maskedHolder = $maskedHolder;
    }

    public function getMaskedHolder()
    {
        return $this->maskedHolder;
    }

    public function setNumber($number)
    {
        $this->securedNumber = SensitiveValue::ensureSensitive($number);
        $this->maskedNumber = Mask::mask($this->securedNumber->peek());

        //BC
        $this->number = $this->securedNumber->peek();
    }

    public function getNumber()
    {
        return $this->securedNumber->peek();
    }

    public function setMaskedNumber($maskedNumber)
    {
        return $this->maskedNumber = $maskedNumber;
    }

    public function getMaskedNumber()
    {
        return $this->maskedNumber;
    }

    public function setSecurityCode($securityCode)
    {
        $this->securedSecurityCode = SensitiveValue::ensureSensitive($securityCode);

        // BC
        $this->securityCode = $this->securedSecurityCode->peek();
    }

    public function getSecurityCode()
    {
        return $this->securedSecurityCode->peek();
    }

    public function getExpireAt()
    {
        return $this->securedExpireAt->peek();
    }

    public function setExpireAt($date = null)
    {
        $date = SensitiveValue::ensureSensitive($date);

        if (false == (null === $date->peek() || $date->peek() instanceof DateTime)) {
            throw new InvalidArgumentException('The date argument must be either instance of DateTime or null');
        }

        $this->securedExpireAt = $date;

        // BC
        $this->expireAt = $this->securedExpireAt->peek();
    }

    public function secure()
    {
        $this->holder = $this->number = $this->expireAt = $this->securityCode = null;
    }
}
