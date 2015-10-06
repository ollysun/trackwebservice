<?php
use Phalcon\Mvc\Model;


/**
 * Class RequestValidator
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class RequestValidator
{
    protected $validation_errors = [];
    protected $parameters;
    protected $fields;
    protected $valid_rules_types = ['isset', 'model'];
    protected $rules = [];
    protected $namespace;

    /**
     * @param array $parameters
     * @param array $fields
     * @param string $namespace
     */
    public function __construct($parameters = [], $fields = [], $namespace = '')
    {
        $this->setParameters($parameters);
        $this->fields = $fields;
        $this->initRules();
        $this->namespace = $namespace;
    }

    /**
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     */
    public function initRules()
    {
        foreach ($this->fields as $field) {
            $this->addRule($field);
        }
    }

    public function addRule($field, $type = 'isset', $config = null)
    {
        $this->rules[$field][] = ['type' => $type, 'field' => $field, 'config' => $config];
        return $this;
    }

    /**
     * Validate all fields
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function validateFields()
    {
        $isValid = true;
        foreach ($this->fields as $field) {
            $rules = $this->rules[$field];
            foreach ($rules as $key => $rule) {
                $isRuleValid = $this->{$rule['type'] . 'Validate'}($rule);
                $isValid = $isRuleValid && $isValid;
                if (!$isRuleValid) {
                    continue;
                }
            }
        }
        return $isValid;
    }

    /**
     * Validate single field
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $rule
     * @return bool
     */
    public function issetValidate($rule)
    {
        if (is_array($this->parameters) && !isset($this->parameters[$rule['field']])) {
            $this->addValidationError($rule['field'], "{{field}} is required");
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
        if (strpos($message, '{{field}}') !== false && strlen($this->namespace) > 0) {
            $message = str_replace('{{field}}', $this->namespace . '.{{field}}', $message);
        }

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
     * @return RequestValidationMessage[]
     */
    public function getMessages()
    {
        return $this->validation_errors;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $parameters
     * @return array
     */
    public function setParameters($parameters)
    {
        $this->parameters = (array)$parameters;
    }

    /**
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @param $rule
     * @return Model
     */
    public function modelValidate($rule)
    {
        $config = $rule['config'];
        $model_name = $config['model'];
        $model = new ReflectionClass($model_name);
        /** @var Model $instance */
        $instance = $model->newInstanceWithoutConstructor();
        if (!$instance::findFirst($this->parameters[$rule['field']])) {
            $this->addValidationError($rule['field'], 'Invalid {{field}} supplied');
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }


}