<?php

/**
 * @author Babatunde Otaru <tunde@cottacush.com>
 */
class CorporateAccountType extends \Phalcon\Mvc\Model
{
    protected $id;
    protected $code;
    protected $acronym;
    protected $name;

    public static function getAll()
    {
        return CorporateAccountType::find()->toArray();
    }
}