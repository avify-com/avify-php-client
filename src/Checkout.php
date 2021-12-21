<?php
declare(strict_types = 1);
namespace App;

use App\Avify;

Class Checkout{
    public $cunrrency;
    public $card_holder;
    public $card_number;
    public $cvc;
    public $exp_month;
    public $exp_year;

    /**
     * Get the value of cunrrency
     */ 
    public function getCunrrency()
    {
        return $this->cunrrency;
    }

    /**
     * Set the value of cunrrency
     *
     * @return  self
     */ 
    public function setCunrrency($cunrrency)
    {
        $this->cunrrency = $cunrrency;

        return $this;
    }

    /**
     * Get the value of card_holder
     */ 
    public function getCard_holder()
    {
        return $this->card_holder;
    }

    /**
     * Set the value of card_holder
     *
     * @return  self
     */ 
    public function setCard_holder($card_holder)
    {
        $this->card_holder = $card_holder;

        return $this;
    }

    /**
     * Get the value of cvc
     */ 
    public function getCvc()
    {
        return $this->cvc;
    }

    /**
     * Set the value of cvc
     *
     * @return  self
     */ 
    public function set_cvc($cvc)
    {
        $this->cvc = $cvc;

        return $this;
    }

    /**
     * Get the value of exp_month
     */ 
    public function get_exp_month()
    {
        return $this->exp_month;
    }

    /**
     * Set the value of exp_month
     *
     * @return  self
     */ 
    public function set_exp_month($exp_month)
    {
        $this->exp_month = $exp_month;

        return $this;
    }

    /**
     * Get the value of exp_year
     */ 
    public function get_exp_year()
    {
        return $this->exp_year;
    }

    /**
     * Set the value of exp_year
     *
     * @return  self
     */ 
    public function set_exp_year($exp_year)
    {
        $this->exp_year = $exp_year;

        return $this;
    }

}