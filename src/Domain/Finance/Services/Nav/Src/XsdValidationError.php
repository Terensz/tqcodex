<?php

namespace Domain\Finance\Services\Nav\Src;

use Exception;

class XsdValidationError extends Exception
{
    protected $errors;

    protected static $levelMap = [
        LIBXML_ERR_WARNING => 'Warning',
        LIBXML_ERR_ERROR => 'Error',
        LIBXML_ERR_FATAL => 'Fatal Error',
    ];

    public function __construct($errors)
    {
        $this->errors = $errors;
        $message = $this->createErrorMessage();
        parent::__construct($message);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function createErrorMessage()
    {
        $messages = [];

        foreach ($this->errors as $error) {
            $messages[] = self::$levelMap[$error->level].': '.$error->message;
        }

        return implode("\n", $messages);
    }
}
