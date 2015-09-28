<?php

class RequestValidationMessagePrinter
{
    protected $messages;

    /**
     * @param RequestValidationMessage[] $messages
     */
    public function __construct($messages = [])
    {
        $this->messages = $messages;
    }

    /**
     * get messages
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return array
     */
    public function getMessages()
    {
        $messages = [];
        foreach ($this->messages as $message) {
            $messages[] = $message->getMessage();
        }
        return $messages;
    }

    /**
     * print messages
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return string
     */
    public function printMessages()
    {
        $messages = $this->getMessages();
        return implode(', ', $messages);
    }
}