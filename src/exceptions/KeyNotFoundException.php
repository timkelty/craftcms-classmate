<?php

namespace timkelty\craftcms\classmate\exceptions;

use yii\base\Exception;

/**
 * @inheritdoc
 */
class KeyNotFoundException extends Exception
{
    protected string $key;

    /**
     * @inheritdoc
     */
    public function __construct(string $key, ?string $message = null)
    {
        $this->key = $key;
        $message = $message ?: "Key not found: $key";

        parent::__construct($message);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'Key not found exception';
    }
}
