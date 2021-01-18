<?php

namespace timkelty\craftcms\classmate\exceptions;

use yii\base\Exception;

class JsonDecodeException extends Exception
{
    protected string $contents;

    public function __construct(string $contents, ?string $message = null)
    {
        $this->contents = $contents;
        $message = $message ?: "Error decoding JSON: $contents";

        parent::__construct($message);
    }

    public function getName(): string
    {
        return 'JSON decode exception';
    }
}
