<?php


/**
 * Class RequestValidator
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class RequestValidator
{
    protected $validation_errors = [];
    protected $parameters;
    protected $required_fields;

    /**
     * @param $parameters
     * @param $required_fields
     */
    public function __construct($parameters = [], $required_fields = [])
    {
        $this->parameters = $parameters;
        $this->required_fields = $required_fields;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return RequestValidationMessage[]
     */
    public function getMessages()
    {
        return $this->validation_errors;
    }

    /**
     * Validate all fields
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function validateFields()
    {
        $isValid = true;
        foreach ($this->required_fields as $field) {
            $isValid = $this->validateField($field) && $isValid;
        }
        return $isValid;
    }

    /**
     * Validate single field
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @return bool
     */
    public function validateField($field)
    {
        if (is_object($this->parameters) && !property_exists($this->parameters, $field)) {
            $this->addValidationError($field, "$field is required");
            return false;
        } else if (is_array($this->parameters) && !isset($this->parameters[$field])) {
            $this->addValidationError($field, "$field is required");
            return false;
        }

        return true;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param $message
     * @return RequestValidationMessage
     */
    private function addValidationError($field, $message)
    {
        $validationMessage = new RequestValidationMessage($field, $message);
        $this->validation_errors[] = $validationMessage;
        return $validationMessage;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return string
     */
    public function printValidationMessage()
    {
        $validationMessagePrinter = new RequestValidationMessagePrinter($this->getMessages());
        return $validationMessagePrinter->printMessages();
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $parameters
     * @return array
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }
}