<?php

use Phalcon\Http\Response;

class PackedResponse extends Response
{
    /**
     * Key placeholders
     */
    const P_STATUS = 'status';
    const P_DATA = 'data';
    const P_MESSAGE = 'message';
    const P_ACCESS_TOKEN = 'access_token';

    /**
     * Standard status codes
     */
    const STATUS_OK = 1;
    const STATUS_ERROR = 2;
    const STATUS_ACCESS_DENIED = 3;
    const STATUS_LOGIN_REQUIRED = 4;
    const STATUS_NOT_FOUND = 5;

    /**
     * Format codes
     */
    const FORMAT_JSON = 1;
    const FORMAT_RAW = 2;

    /**
     * Sets content as a success
     *
     * @param mixed $data
     * @param int $format
     * @return $this
     */
    public function sendSuccess($data = '', $format = self::FORMAT_JSON)
    {
        $package = array(
            self::P_STATUS => self::STATUS_OK,
            self::P_DATA => $data,
            self::P_ACCESS_TOKEN => $this->getDI()->getAuth()->getToken()
        );
        $this->setContentByFormat($package, $format);
        return $this;
    }

    /**
     * Sets content as a error
     *
     * @param string $message
     * @param int $format
     * @return $this
     */
    public function sendError($message = 'An error has occurred', $format = self::FORMAT_JSON)
    {
        $package = array(
            self::P_STATUS => self::STATUS_ERROR,
            self::P_MESSAGE => $message,
            self::P_ACCESS_TOKEN => $this->getDI()->getAuth()->getToken()
        );
        $this->setContentByFormat($package, $format);
        return $this;
    }

    /**
     * Sets content as an access denied package
     *
     * @param int $format
     * @return $this
     */
    public function sendAccessDenied($format = self::FORMAT_JSON)
    {
        $package = array(
            self::P_STATUS => self::STATUS_ACCESS_DENIED,
            self::P_MESSAGE => 'Access Denied!'
        );
        $this->setContentByFormat($package, $format);
        return $this;
    }

    /**
     * Sets content to indicate login is required
     *
     * @param int $format
     * @return $this
     */
    public function sendLoginRequired($format = self::FORMAT_JSON)
    {
        $package = array(
            self::P_STATUS => self::STATUS_LOGIN_REQUIRED,
            self::P_MESSAGE => 'Login Required'
        );
        $this->setContentByFormat($package, $format);
        return $this;
    }

    /**
     * Sets content with custom attributes (status, data and message)
     *
     * @param int $status
     * @param mixed $data
     * @param string $message
     * @param int $format
     * @return $this
     */
    public function sendCustom($status, $data = null, $message = null, $format = self::FORMAT_JSON)
    {
        $package = array(
            self::P_STATUS => $status,
            self::P_DATA => $data,
            self::P_MESSAGE => $message,
            self::P_ACCESS_TOKEN => $this->getDI()->getAuth()->getToken()
        );
        $this->setContentByFormat($package, $format);
        return $this;
    }

    /**
     * Sets the content to conform with a particular data format
     *
     * @param mixed $content
     * @param int $format
     */
    private function setContentByFormat($content, $format)
    {
        switch ($format) {
            case self::FORMAT_JSON:
                $this->setContentType('application/json', 'UTF-8');
                $content = json_encode($content);
                break;
            case self::FORMAT_RAW:
            default:
                $this->setContentType('text/plain', 'UTF-8');
                break;
        }
        $this->setContent($content);
    }
} 