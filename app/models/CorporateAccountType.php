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

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @return array
     */
    public static function getAll()
    {
        return CorporateAccountType::find()->toArray();
    }
}