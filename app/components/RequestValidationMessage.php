<?php

/**
 * Class RequestValidationMessage
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class RequestValidationMessage
{

    private $field;
    private $message;

    /**
     * @param $field
     * @param $message
     */
    public function __construct($field, $message)
    {
        $this->field = $field;
        $this->message = $message;
    }

    /**
     * Get field
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Get Message
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }


}