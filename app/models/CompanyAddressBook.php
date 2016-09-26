<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 9/6/2016
 * Time: 2:56 PM
 */
class CompanyAddressBook extends \Phalcon\Mvc\Model
{
    protected $company_id;

    protected $first_name;

    protected $last_name;

    protected $email;

    protected $phone;

    protected $address_street1;

    protected $address_street2;

    protected $country_id;

    protected $state_id;

    protected $city_id;

    public function getCompanyId(){
        return $this->company_id;
    }
    public function setCompanyId($company_id){
        $this->company_id = $company_id;
        return $this;
    }

    public function getFirstName(){
        return $this->first_name;
    }
    public function setFirstName($first_name){
        $this->first_name = $first_name;
        return $this;
    }

    public function getLastName(){
        return $this->last_name;
    }
    public function setLastName($last_name){
        $this->last_name = $last_name;
        return $this;
    }

    public function getEmail(){
        return $this->email;
    }
    public function setEmail($email){
        $this->email = $email;
        return $this;
    }

    public function getPhone(){
        return $this->phone;
    }
    public function setPhone($phone){
        $this->phone = $phone;
        return $this;
    }

    public function getAddressStreet1(){
        return $this->address_street1;
    }
    public function setAddressStreet1($address_street1){
        $this->address_street1 = $address_street1;
        return $this;
    }

    public function getAddressStreet2(){
        return $this->address_street2;
    }
    public function setAddressStreet2($address_street2){
        $this->address_street2 = $address_street2;
        return $this;
    }

    public function getCountryId(){
        return $this->country_id;
    }
    public function setCountryId($country_id){
        $this->country_id = $country_id;
        return $this;
    }

    public function getStateId(){
        return $this->state_id;
    }
    public function setStateId($state_id){
        $this->state_id = $state_id;
        return $this;
    }

    public function getCityId(){
        return $this->city_id;
    }
    public function setCityId($city_id){
        $this->city_id = $city_id;
        return $city_id;
    }
}