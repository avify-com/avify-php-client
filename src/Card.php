<?php

declare(strict_types=1);

namespace App;


class Card
{
    public $cardHolder;
    public $cardNumber;
    public $cvc;
    public $expMonth;
    public $expYear;

    public function getCardHolder()
    {
        return $this->cardHolder;
    }

    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    public function getCVC()
    {
        return $this->cvc;
    }

    public function getExpMonth()
    {
        return $this->expMonth;
    }

    public function getExpYear()
    {
        return $this->expYear;
    }

    public function setCardHolder($cardHolder)
    {
        $this->cardHolder = $cardHolder;

        return $this;
    }

    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function setCVC($cvc)
    {
        $this->cvc = $cvc;

        return $this;
    }

    public function setExpMonth($expMonth)
    {
        $this->expMonth = $expMonth;

        return $this;
    }

    public function setExpYear($expYear)
    {
        $this->expYear = $expYear;

        return $this;
    }
}
