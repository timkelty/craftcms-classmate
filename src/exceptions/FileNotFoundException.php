<?php

namespace timkelty\craftcms\classmate\exceptions;

use yii\base\Exception;

/**
 * @inheritdoc
 */
class FileNotFoundException extends Exception
{
    protected string $filePath;

    /**
     * @inheritdoc
     */
    public function __construct(string $filePath, ?string $message = null)
    {
        $this->filePath = $filePath;
        $message = $message ?: "File not found: $filePath";

        parent::__construct($message);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'File not found exception';
    }
}
