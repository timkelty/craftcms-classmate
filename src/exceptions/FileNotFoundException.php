<?php

namespace timkelty\craftcms\classmate\exceptions;

use yii\base\Exception;

class FileNotFoundException extends Exception
{
    protected $filePath;

    public function __construct(string $filePath, ?string $message = null)
    {
        $this->filePath = $filePath;
        $message = $message ?: "File not found: $filePath";

        parent::__construct($message);
    }

    public function getName(): string
    {
        return 'File not found exception';
    }
}
